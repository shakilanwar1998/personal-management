<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailRequest;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Send an email
     *
     * @param SendEmailRequest $request
     * @return JsonResponse
     */
    public function sendEmail(SendEmailRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->emailService->sendEmail(
            to: $validated['to'],
            subject: $validated['subject'],
            body: $validated['body'],
            from: $validated['from'] ?? null,
            fromName: $validated['from_name'] ?? null,
            attachments: $validated['attachments'] ?? []
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'to' => $result['to'],
                    'subject' => $result['subject'],
                    'sent_at' => now()->toISOString(),
                ]
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'error' => $result['error'] ?? null,
        ], 500);
    }

    /**
     * Send an HTML email
     *
     * @param SendEmailRequest $request
     * @return JsonResponse
     */
    public function sendHtmlEmail(SendEmailRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->emailService->sendHtmlEmail(
            to: $validated['to'],
            subject: $validated['subject'],
            htmlBody: $validated['body'],
            from: $validated['from'] ?? null,
            fromName: $validated['from_name'] ?? null,
            attachments: $validated['attachments'] ?? []
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'to' => $result['to'],
                    'subject' => $result['subject'],
                    'sent_at' => now()->toISOString(),
                ]
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'error' => $result['error'] ?? null,
        ], 500);
    }

    /**
     * Send email with automatic format detection
     *
     * @param SendEmailRequest $request
     * @return JsonResponse
     */
    public function sendEmailAuto(SendEmailRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $isHtml = $validated['is_html'] ?? false;

        if ($isHtml) {
            return $this->sendHtmlEmail($request);
        }

        return $this->sendEmail($request);
    }

    /**
     * Test email configuration
     *
     * @return JsonResponse
     */
    public function testConfiguration(): JsonResponse
    {
        try {
            $result = $this->emailService->sendEmail(
                to: env('EMAIL_USER', 'noreply@email.okkhor.com'),
                subject: 'SMTP Configuration Test',
                body: 'This is a test email to verify SMTP configuration is working correctly.',
                from: env('EMAIL_USER', 'noreply@email.okkhor.com'),
                fromName: 'System Test'
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'SMTP configuration test successful',
                    'data' => [
                        'smtp_host' => env('SMTP_HOST', 'mail.mysecurecloudhost.com'),
                        'smtp_port' => env('SMTP_PORT', '465'),
                        'email_user' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                        'test_sent_to' => env('EMAIL_USER', 'noreply@email.okkhor.com'),
                    ]
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'SMTP configuration test failed',
                'error' => $result['error'] ?? 'Unknown error',
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMTP configuration test failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
