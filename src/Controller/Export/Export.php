<?php

declare(strict_types=1);

namespace App\Controller\Export;

use App\Form\ExportType;
use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException;
use Netgen\IbexaSiteApi\API\Exceptions\TranslationNotMatchedException;
use Netgen\IbexaSiteApi\API\LoadService;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

use function basename;
use function file_put_contents;
use function is_dir;
use function mkdir;
use function sprintf;
use function str_replace;

class Export extends AbstractController
{
    private const MIGRATIONS_DIRECTORY = 'var/cache/migrations/';
    private LoadService $loadService;

    public function __construct(LoadService $loadService)
    {
        $this->loadService = $loadService;
    }

    public function __invoke(Request $request): Response
    {
        $phpFinder = new PhpExecutableFinder();

        $phpPath = $phpFinder->find();

        if ($phpPath === false) {
            throw new RuntimeException('The php executable could not be found. It is needed for executing parallel subprocesses, so add it to your PATH environment variable and try again.');
        }

        if (!is_dir('../' . self::MIGRATIONS_DIRECTORY)) {
            mkdir('../' . self::MIGRATIONS_DIRECTORY, 0777, true);
        }

        $form = $this->createForm(ExportType::class);
        $form->handleRequest($request);
        $fileName = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $contentId = (int) $form->get('source')->getData();
            $migrationType = $form->get('migration_type')->getData();
            $sourceStructure = $form->get('source_structure')->getData();

            try {
                $content = $this->loadService->loadContent($contentId);
            } catch (NotFoundException|TranslationNotMatchedException|UnauthorizedException $e) {
                $this->addFlash('error', $e->getMessage());

                return $this->render(
                    'export.html.twig',
                    [
                        'form' => $form->createView(),
                        'fileName' => $fileName,
                    ],
                );
            }

            if ($sourceStructure === 'subtree') {
                $subtreePath = $content->mainLocation?->pathString;
                $process = new Process([$phpPath, '../bin/console', 'kaliop:migration:generate', '--type=content', '--match-type=subtree', '--match-value=' . $subtreePath, '--mode=' . $migrationType, '../' . $this::MIGRATIONS_DIRECTORY, $migrationType . '_subtree']);
            } else {
                $process = new Process([$phpPath, '../bin/console', 'kaliop:migration:generate', '--type=content', '--match-type=content_id', '--match-value=' . $contentId, '--mode=' . $migrationType, '../' . $this::MIGRATIONS_DIRECTORY, $migrationType . '_content']);
            }
            $process->run();
            if (!$process->isSuccessful()) {
                $error = sprintf(
                    'The command "%s" failed. Exit Code: %s(%s) Working directory: %s',
                    $process->getCommandLine(),
                    $process->getExitCode(),
                    $process->getExitCodeText(),
                    $process->getWorkingDirectory(),
                );
                $this->addFlash('error', $error);
            } else {
                $message = $process->getOutput();
                $this->addFlash('success', $message);

                $fileName = str_replace("\n", '', basename($process->getOutput()));
                $projectRoot = $this->getParameter('kernel.project_dir');
                $filePath = $projectRoot . '/' . $this::MIGRATIONS_DIRECTORY . $fileName;
                $yamlParsed = Yaml::parseFile($filePath);
                /*
                 * @phpstan-ignore-next-line
                 */
                foreach ($yamlParsed as &$content) {
                    if ($migrationType === 'create') {
                        $location = $this->loadService->loadLocation($content['parent_location']);
                        $locationRemoteId = $location->remoteId;
                        $content['parent_location'] = $locationRemoteId;
                    }
                }

                $yaml = Yaml::dump($yamlParsed);
                file_put_contents($filePath, $yaml);
            }
        }

        return $this->render(
            'export.html.twig',
            [
                'form' => $form->createView(),
                'file' => $fileName ?? null,
            ],
        );
    }
}
