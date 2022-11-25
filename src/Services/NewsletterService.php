<?php

declare(strict_types=1);

namespace App\Services;

use eZ\Publish\API\Repository\Values\Content\Content;
use Ibexa\Contracts\ContentForms\Data\Content\FieldData;
use Ibexa\Core\FieldType\Checkbox\Value as CheckboxValue;
use MailerLiteApi\MailerLite;
use RuntimeException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use function array_filter;
use function array_unique;
use function ctype_digit;
use function explode;
use function is_array;
use function preg_match;
use function reset;

final class NewsletterService
{
    public const ALREADY_ACTIVE = 'already_active';
    public const NEW_UNCONFIRMED = 'new';
    public const PREVIOUS_UNCONFIRMED = 'unconfirmed';
    public const UNSUBSCRIBED = 'unsubscribed';

    private MailerInterface $mailer;
    private MailerLite $mailerLite;
    private TranslatorInterface $translator;
    private string $newsletterSenderEmail;
    private string $newsletterRecipientEmail;

    public function __construct(
        MailerLite $mailerLite,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        string $newsletterSenderEmail,
        string $newsletterRecipientEmail
    ) {
        $this->mailerLite = $mailerLite;
        $this->mailer = $mailer;
        $this->translator = $translator;

        $this->newsletterSenderEmail = $newsletterSenderEmail;
        $this->newsletterRecipientEmail = $newsletterRecipientEmail;
    }

    /**
     * @param \Ibexa\Contracts\ContentForms\Data\Content\FieldData[] $fields
     *
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function subscribeToNewsletters(Content $content, array $fields): void
    {
        foreach ($fields as $field) {
            if ($this->isFieldValidNewsletterOptIn($field)) {
                $this->subscribeToNewsletter($content, $fields, $field);
            }
        }
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function subscribeToNewsletter(Content $content, array $fields, FieldData $field): void
    {
        $identifier = $this->extractOptInIdentifier($field);

        $subscriberData = $this->extractSubscriberData($fields);
        $mailerLiteGroupIds = $this->extractMailerLiteGroupIds($content, $identifier);

        $status = [];

        foreach ($mailerLiteGroupIds as $mailerLiteGroupId) {
            $mailerLiteResponse = $this->addSubscriberToGroup((int) $mailerLiteGroupId, $subscriberData);

            if (isset($mailerLiteResponse->error)) {
                throw new RuntimeException('MailerLite error');
            }

            $subscriberId = $mailerLiteResponse->id;

            if ($mailerLiteResponse->type === 'unconfirmed' && $mailerLiteResponse->sent === 1) {
                $status[] = self::PREVIOUS_UNCONFIRMED;
            } elseif ($mailerLiteResponse->type === 'active') {
                $status[] = self::ALREADY_ACTIVE;
            } elseif ($mailerLiteResponse->type === 'unsubscribed') {
                $status[] = self::UNSUBSCRIBED;
            } else {
                $status[] = self::NEW_UNCONFIRMED;
            }

            $currentStatus = array_unique($status);
            $currentStatus = reset($currentStatus);

            if ($currentStatus === self::UNSUBSCRIBED) {
                $this->sendUnsubscribedWarningMail($subscriberId);
            } elseif ($currentStatus === self::PREVIOUS_UNCONFIRMED) {
                $this->sendPreviouslyUnconfirmedMail($subscriberId);
            }
        }
    }

    private function isFieldValidNewsletterOptIn(FieldData $field): bool
    {
        $identifier = $this->extractOptInIdentifier($field);

        if ($identifier === '') {
            return false;
        }

        $value = $field->value;

        if (!$value instanceof CheckboxValue) {
            return false;
        }

        return $value->bool;
    }

    private function extractOptInIdentifier(FieldData $field): string
    {
        $identifier = $field->fieldDefinition->identifier;
        $success = preg_match('/^newsletter_opt_in_(?<identifier>.*?)_choice/', $identifier, $matches);

        if ($success === false) {
            throw new RuntimeException('Error matching opt-in identifier');
        }

        return $matches['identifier'] ?? '';
    }

    private function extractSubscriberData(array $fields): array
    {
        return [
            'email' => isset($fields['email']) ? $fields['email']->value->email : null,
            'fields' => [
                'name' => isset($fields['sender_name']) ? $fields['sender_name']->value->text : '',
                'last_name' => isset($fields['sender_last_name']) ? $fields['sender_last_name']->value->text : '',
                'company' => isset($fields['sender_company']) ? $fields['sender_company']->value->text : '',
            ],
        ];
    }

    private function extractMailerLiteGroupIds(Content $content, string $identifier): array
    {
        $groupIdsFieldIdentifier = 'newsletter_opt_in_' . $identifier . '_group_ids';
        $mailerLiteGroupIds = $content->getFieldValue($groupIdsFieldIdentifier)->text;
        $mailerLiteGroupIds = !empty($mailerLiteGroupIds) ? explode(' ', $mailerLiteGroupIds) : [];

        return is_array($mailerLiteGroupIds)
            ? array_filter(
                $mailerLiteGroupIds,
                static fn ($mailerLiteGroupId) => ctype_digit($mailerLiteGroupId),
            )
            : [];
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function sendUnsubscribedWarningMail(int $subscriberId): void
    {
        $message = new Email();

        $subject = $this->translator->trans('newsletter.unsubscribed_person_subscribed.subject');
        $body = $this->translator->trans('newsletter.unsubscribed_person_subscribed.body');
        $body .= "\nhttps://app.mailerlite.com/subscribers/single/" . $subscriberId;

        $message->addFrom(Address::create($this->newsletterSenderEmail));
        $message->addTo(Address::create($this->newsletterRecipientEmail));
        $message->subject($subject);
        $message->text($body);

        $this->mailer->send($message);
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function sendPreviouslyUnconfirmedMail(int $subscriberId): void
    {
        $message = new Email();

        $subject = $this->translator->trans('newsletter.previously_unconfirmed.subject');
        $body = $this->translator->trans('newsletter.previously_unconfirmed.body');
        $body .= "\nhttps://app.mailerlite.com/subscribers/single/" . $subscriberId;

        $message->addFrom(Address::create($this->newsletterSenderEmail));
        $message->addTo(Address::create($this->newsletterRecipientEmail));
        $message->subject($subject);
        $message->text($body);

        $this->mailer->send($message);
    }

    /**
     * @param int $groupId
     * @param array $subscriberData
     * @param array|null $params
     *
     * @return mixed
     */
    private function addSubscriberToGroup(int $groupId, array $subscriberData, ?array $params = [])
    {
        return $this->mailerLite->groups()->addSubscriber($groupId, $subscriberData, $params);
    }

    /**
     * @param int $groupId
     * @param int $subscriberId
     *
     * @return mixed
     */
    private function removeSubscriberFromGroup(int $groupId, int $subscriberId)
    {
        return $this->mailerLite->groups()->removeSubscriber($groupId, $subscriberId);
    }

    /**
     * @param int $groupId
     * @param string|null $type
     * @param array|null $params
     *
     * @return mixed
     */
    private function getGroupSubscribers(int $groupId, ?string $type = null, ?array $params = [])
    {
        return $this->mailerLite->groups()->getSubscribers($groupId, $type, $params);
    }
}
