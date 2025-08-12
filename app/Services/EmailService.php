<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class EmailService
{
    /**
     * Send an email using the configured SMTP settings
     *
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string|null $from
     * @param string|null $fromName
     * @param array $attachments
     * @return array
     */
    public function sendEmail(
        string $to,
        string $subject,
        string $body,
        ?string $from = null,
        ?string $fromName = null,
        array $attachments = []
    ): array {
        try {
            // Configure mail settings
            config([
                'mail.mailers.smtp.host' => env('SMTP_HOST', 'mail.mysecurecloudhost.com'),
                'mail.mailers.smtp.port' => env('SMTP_PORT', '465'),
                'mail.mailers.smtp.encryption' => 'ssl',
                'mail.mailers.smtp.username' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'mail.mailers.smtp.password' => env('EMAIL_PASSWORD', '{Oq?t,6.S9#^uv67'),
                'mail.from.address' => $from ?? env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'mail.from.name' => $fromName ?? 'Okkhor.com Ltd.',
            ]);

            // Create mail data
            $mailData = [
                'subject' => $subject,
                'body' => $body,
            ];

            // Send email
            Mail::send('emails.generic', $mailData, function ($message) use ($to, $subject, $from, $fromName, $attachments) {
                $message->to($to)
                        ->subject($subject);

                if ($from) {
                    $message->from($from, $fromName);
                }

                // Add attachments if any
                foreach ($attachments as $attachment) {
                    if (isset($attachment['path']) && file_exists($attachment['path'])) {
                        $message->attach($attachment['path'], [
                            'as' => $attachment['name'] ?? basename($attachment['path']),
                            'mime' => $attachment['mime'] ?? null,
                        ]);
                    }
                }
            });

            Log::info('Email sent successfully', [
                'to' => $to,
                'subject' => $subject,
                'from' => $from ?? env('EMAIL_USER'),
            ]);

            return [
                'success' => true,
                'message' => 'Email sent successfully',
                'to' => $to,
                'subject' => $subject,
            ];

        } catch (Exception $e) {
            Log::error('Failed to send email', [
                'error' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send HTML email
     *
     * @param string $to
     * @param string $subject
     * @param string $htmlBody
     * @param string|null $from
     * @param string|null $fromName
     * @param array $attachments
     * @return array
     */
    public function sendHtmlEmail(
        string $to,
        string $subject,
        string $htmlBody,
        ?string $from = null,
        ?string $fromName = null,
        array $attachments = []
    ): array {
        try {
            // Configure mail settings
            config([
                'mail.mailers.smtp.host' => env('SMTP_HOST', 'mail.mysecurecloudhost.com'),
                'mail.mailers.smtp.port' => env('SMTP_PORT', '465'),
                'mail.mailers.smtp.encryption' => 'ssl',
                'mail.mailers.smtp.username' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'mail.mailers.smtp.password' => env('EMAIL_PASSWORD', '{Oq?t,6.S9#^uv67'),
                'mail.from.address' => $from ?? env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'mail.from.name' => $fromName ?? 'Personal Management System',
            ]);

            // Send HTML email
            Mail::html($htmlBody, function ($message) use ($to, $subject, $from, $fromName, $attachments) {
                $message->to($to)
                        ->subject($subject);

                if ($from) {
                    $message->from($from, $fromName);
                }

                // Add attachments if any
                foreach ($attachments as $attachment) {
                    if (isset($attachment['path']) && file_exists($attachment['path'])) {
                        $message->attach($attachment['path'], [
                            'as' => $attachment['name'] ?? basename($attachment['path']),
                            'mime' => $attachment['mime'] ?? null,
                        ]);
                    }
                }
            });

            Log::info('HTML email sent successfully', [
                'to' => $to,
                'subject' => $subject,
                'from' => $from ?? env('EMAIL_USER'),
            ]);

            return [
                'success' => true,
                'message' => 'HTML email sent successfully',
                'to' => $to,
                'subject' => $subject,
            ];

        } catch (Exception $e) {
            Log::error('Failed to send HTML email', [
                'error' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send HTML email: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }
}
