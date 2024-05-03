<?php

declare(strict_types=1);

namespace App\Security;

use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\Values\Content\LocationQuery;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion;
use Ibexa\Contracts\Core\Repository\Values\Content\Search\SearchResult;
use Ibexa\Contracts\Core\Repository\Values\User\User;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Netgen\IbexaSiteApi\API\FilterService;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\IbexaSiteApi\API\Values\Location;
use Netgen\IbexaSiteApi\Core\Traits\SearchResultExtractorTrait;

use function array_filter;
use function array_map;
use function array_unique;
use function count;
use function in_array;

final class UserRoleResolver
{
    use SearchResultExtractorTrait;

    /** @var array<string, int[]> */
    private array $rolesByUserGroups;

    public function __construct(
        private Repository $repository,
        private LoadService $loadService,
        private FilterService $filterService,
        private ConfigResolverInterface $configResolver,
    ) {
        $this->rolesByUserGroups = $this->configResolver->getParameter('roles_by_user_groups', 'ngsite');
    }

    /**
     * @return string[]
     */
    public function resolveRoles(User $user): array
    {
        $roles = [];

        /** @var \Netgen\IbexaSiteApi\API\Values\Location[] $locations */
        $locations = $this->repository->sudo(
            fn () => $this->loadService->loadContent($user->contentInfo->id)->getLocations(),
        );

        $locationPathStrings = array_map(
            static fn (Location $location): string => $location->pathString,
            $locations,
        );

        if (count($locationPathStrings) === 0) {
            return [];
        }

        try {
            $query = new LocationQuery(
                [
                    'filter' => new Criterion\LogicalAnd(
                        [
                            new Criterion\Ancestor($locationPathStrings),
                            new Criterion\ContentTypeIdentifier('user_group'),
                        ],
                    ),
                    'limit' => 9999,
                ],
            );

            $result = $this->repository->sudo(
                fn (): SearchResult => $this->filterService->filterLocations($query),
            );

            $userGroupLocations = $this->extractLocations($result);

            foreach ($userGroupLocations as $userGroupLocation) {
                $roles = [...$roles, ...$this->getUserGroupLocationRole($userGroupLocation)];
            }
        } catch (InvalidArgumentException) {
            return [];
        }

        return array_filter(array_unique($roles));
    }

    /**
     * @return iterable<string>
     */
    private function getUserGroupLocationRole(Location $location): iterable
    {
        foreach ($this->rolesByUserGroups as $role => $userGroups) {
            if (in_array($location->id, $userGroups, true)) {
                yield $role;
            }
        }
    }
}
