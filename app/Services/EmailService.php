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
        // Try different SMTP configurations
        $configs = [
            [
                'host' => env('SMTP_HOST', 's1310.sgp1.mysecurecloudhost.com'),
                'port' => env('SMTP_PORT', '465'),
                'encryption' => 'ssl',
                'username' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'password' => env('EMAIL_PASSWORD', '{Oq?t,6.S9#^uv67'),
            ],
            [
                'host' => env('SMTP_HOST', 's1310.sgp1.mysecurecloudhost.com'),
                'port' => env('SMTP_PORT', '587'),
                'encryption' => 'tls',
                'username' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'password' => env('EMAIL_PASSWORD', '{Oq?t,6.S9#^uv67'),
            ],
            [
                'host' => env('SMTP_HOST', 's1310.sgp1.mysecurecloudhost.com'),
                'port' => env('SMTP_PORT', '465'),
                'encryption' => 'ssl',
                'username' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'password' => env('EMAIL_PASSWORD', '{Oq?t,6.S9#^uv67'),
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
            [
                'host' => env('SMTP_HOST', 's1310.sgp1.mysecurecloudhost.com'),
                'port' => env('SMTP_PORT', '465'),
                'encryption' => 'ssl',
                'username' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'password' => env('EMAIL_PASSWORD', '{Oq?t,6.S9#^uv67'),
                'timeout' => 30,
                'local_domain' => env('MAIL_EHLO_DOMAIN', 'localhost'),
            ]
        ];

        $lastError = null;

        foreach ($configs as $index => $config) {
            try {
                Log::info("Trying SMTP configuration #" . ($index + 1), $config);
                
                // Configure mail settings
                config([
                    'mail.mailers.smtp.host' => $config['host'],
                    'mail.mailers.smtp.port' => $config['port'],
                    'mail.mailers.smtp.encryption' => $config['encryption'],
                    'mail.mailers.smtp.username' => $config['username'],
                    'mail.mailers.smtp.password' => $config['password'],
                    'mail.mailers.smtp.verify_peer' => $config['verify_peer'] ?? true,
                    'mail.mailers.smtp.verify_peer_name' => $config['verify_peer_name'] ?? true,
                    'mail.mailers.smtp.timeout' => $config['timeout'] ?? null,
                    'mail.mailers.smtp.local_domain' => $config['local_domain'] ?? null,
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

                Log::info('Email sent successfully with config #' . ($index + 1), [
                    'to' => $to,
                    'subject' => $subject,
                    'from' => $from ?? env('EMAIL_USER'),
                    'config' => $config,
                ]);

                return [
                    'success' => true,
                    'message' => 'Email sent successfully',
                    'to' => $to,
                    'subject' => $subject,
                    'config_used' => $index + 1,
                ];

            } catch (Exception $e) {
                $lastError = $e;
                Log::warning('SMTP configuration #' . ($index + 1) . ' failed', [
                    'error' => $e->getMessage(),
                    'config' => $config,
                ]);
                
                // Continue to next configuration
                continue;
            }
        }

        // If we get here, all configurations failed
        Log::error('All SMTP configurations failed', [
            'error' => $lastError ? $lastError->getMessage() : 'Unknown error',
            'to' => $to,
            'subject' => $subject,
        ]);

        return [
            'success' => false,
            'message' => 'Failed to send email: All SMTP configurations failed. Last error: ' . ($lastError ? $lastError->getMessage() : 'Unknown error'),
            'error' => $lastError ? $lastError->getMessage() : 'Unknown error',
        ];
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
        // Try different SMTP configurations
        $configs = [
            [
                'host' => env('SMTP_HOST', 's1310.sgp1.mysecurecloudhost.com'),
                'port' => env('SMTP_PORT', '465'),
                'encryption' => 'ssl',
                'username' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'password' => env('EMAIL_PASSWORD', '{Oq?t,6.S9#^uv67'),
            ],
            [
                'host' => env('SMTP_HOST', 's1310.sgp1.mysecurecloudhost.com'),
                'port' => env('SMTP_PORT', '587'),
                'encryption' => 'tls',
                'username' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'password' => env('EMAIL_PASSWORD', '{Oq?t,6.S9#^uv67'),
            ],
            [
                'host' => env('SMTP_HOST', 's1310.sgp1.mysecurecloudhost.com'),
                'port' => env('SMTP_PORT', '465'),
                'encryption' => 'ssl',
                'username' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'password' => env('EMAIL_PASSWORD', '{Oq?t,6.S9#^uv67'),
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
            [
                'host' => env('SMTP_HOST', 's1310.sgp1.mysecurecloudhost.com'),
                'port' => env('SMTP_PORT', '465'),
                'encryption' => 'ssl',
                'username' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                'password' => env('EMAIL_PASSWORD', '{Oq?t,6.S9#^uv67'),
                'timeout' => 30,
                'local_domain' => env('MAIL_EHLO_DOMAIN', 'localhost'),
            ]
        ];

        $lastError = null;

        foreach ($configs as $index => $config) {
            try {
                Log::info("Trying HTML SMTP configuration #" . ($index + 1), $config);
                
                // Configure mail settings
                config([
                    'mail.mailers.smtp.host' => $config['host'],
                    'mail.mailers.smtp.port' => $config['port'],
                    'mail.mailers.smtp.encryption' => $config['encryption'],
                    'mail.mailers.smtp.username' => $config['username'],
                    'mail.mailers.smtp.password' => $config['password'],
                    'mail.mailers.smtp.verify_peer' => $config['verify_peer'] ?? true,
                    'mail.mailers.smtp.verify_peer_name' => $config['verify_peer_name'] ?? true,
                    'mail.mailers.smtp.timeout' => $config['timeout'] ?? null,
                    'mail.mailers.smtp.local_domain' => $config['local_domain'] ?? null,
                    'mail.from.address' => $from ?? env('EMAIL_USER', 'noreply@email.okkhor.com'),
                    'mail.from.name' => $fromName ?? 'Okkhor.com Ltd.',
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

                Log::info('HTML email sent successfully with config #' . ($index + 1), [
                    'to' => $to,
                    'subject' => $subject,
                    'from' => $from ?? env('EMAIL_USER'),
                    'config' => $config,
                ]);

                return [
                    'success' => true,
                    'message' => 'HTML email sent successfully',
                    'to' => $to,
                    'subject' => $subject,
                    'config_used' => $index + 1,
                ];

            } catch (Exception $e) {
                $lastError = $e;
                Log::warning('HTML SMTP configuration #' . ($index + 1) . ' failed', [
                    'error' => $e->getMessage(),
                    'config' => $config,
                ]);
                
                // Continue to next configuration
                continue;
            }
        }

        // If we get here, all configurations failed
        Log::error('All HTML SMTP configurations failed', [
            'error' => $lastError ? $lastError->getMessage() : 'Unknown error',
            'to' => $to,
            'subject' => $subject,
        ]);

        return [
            'success' => false,
            'message' => 'Failed to send HTML email: All SMTP configurations failed. Last error: ' . ($lastError ? $lastError->getMessage() : 'Unknown error'),
            'error' => $lastError ? $lastError->getMessage() : 'Unknown error',
        ];
    }
}
