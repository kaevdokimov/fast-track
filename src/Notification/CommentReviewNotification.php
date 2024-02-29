<?php

namespace App\Notification;

use App\Entity\Comment;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\InlineKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\ChatNotificationInterface;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class CommentReviewNotification extends Notification implements EmailNotificationInterface, ChatNotificationInterface
{
    public function __construct(
        private readonly Comment $comment,
        private readonly string  $reviewUrl,
    )
    {
        $message = sprintf('%s (%s) says: %s', $this->comment->getAuthor(), $this->comment->getEmail(), $this->comment->getText());
        parent::__construct("New comment posted\n" . $message);
    }

    public function asEmailMessage(EmailRecipientInterface $recipient, ?string $transport = null): ?EmailMessage
    {
        $message = EmailMessage::fromNotification($this, $recipient, $transport);
        $message->getMessage()
            ->htmlTemplate('emails/comment_notification.html.twig')
            ->context(['comment' => $this->comment]);

        return $message;
    }

    public function getChannels(RecipientInterface $recipient): array
    {
        if (preg_match('{\b(great|awesome|отличный|потрясающий)\b}i', $this->comment->getText())) {
            return ['email', 'chat/telegram'];
        }

        $this->importance(Notification::IMPORTANCE_LOW);

        return ['email'];
    }

    // TODO: make working buttons in Telegram
    public function asChatMessage(RecipientInterface $recipient, ?string $transport = null): ?ChatMessage
    {
        if ('telegram' !== $transport) {
            return null;
        }

        $message = new ChatMessage(sprintf('%s (%s) says: %s', $this->comment->getAuthor(), $this->comment->getEmail(), $this->comment->getText()));
        $message->subject($this->getSubject());
        $message->options((new TelegramOptions())
            ->parseMode('MarkdownV2')
            ->disableWebPagePreview(true)
            ->replyMarkup((new InlineKeyboardMarkup())
//                ->inlineKeyboard([
//                    (new InlineKeyboardButton('Accept')),
//                        //->url($this->reviewUrl),
//                    (new InlineKeyboardButton('Reject')),
//                        //->url($this->reviewUrl.'?reject=1'),
//                ])
            ));

        return $message;
    }
}
