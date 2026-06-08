<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle GitHub webhook for automatic git pull
     */
    public function handleGithubWebhook(Request $request)
    {
        $this->logWebhookRequest('GitHub', $request);

        // Verify webhook signature (optional but recommended)
        $signature = $request->header('X-Hub-Signature-256');
        $payload = $request->getContent();
        
        // If you have a webhook secret, verify it
        if (!$this->verifyGithubSignature($payload, $signature)) {
            $this->logWebhookError('GitHub', 'Invalid signature');
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        try {
            // Pull the latest changes from git
            $this->gitPull();
            
            // Optionally run additional commands after pull
            $this->runPostPullCommands();
            
            $this->logWebhookSuccess('GitHub', 'Git pull completed successfully');
            return response()->json(['message' => 'Git pull completed successfully'], 200);
        } catch (\Exception $e) {
            $this->logWebhookError('GitHub', $e);
            return response()->json(['message' => 'Error during git pull', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle GitLab webhook for automatic git pull
     */
    public function handleGitlabWebhook(Request $request)
    {
        $this->logWebhookRequest('GitLab', $request);

        // Verify webhook token if configured
        $token = $request->header('X-Gitlab-Token');
        $webhookSecret = config('app.gitlab_webhook_secret');
        
        if ($webhookSecret && $token !== $webhookSecret) {
            $this->logWebhookError('GitLab', 'Invalid token');
            return response()->json(['message' => 'Invalid token'], 401);
        }

        try {
            // Pull the latest changes from git
            $this->gitPull();
            
            // Optionally run additional commands after pull
            $this->runPostPullCommands();
            
            $this->logWebhookSuccess('GitLab', 'Git pull completed successfully');
            return response()->json(['message' => 'Git pull completed successfully'], 200);
        } catch (\Exception $e) {
            $this->logWebhookError('GitLab', $e);
            return response()->json(['message' => 'Error during git pull', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle Bitbucket webhook for automatic git pull
     */
    public function handleBitbucketWebhook(Request $request)
    {
        $this->logWebhookRequest('Bitbucket', $request);

        try {
            // Pull the latest changes from git
            $this->gitPull();
            
            // Optionally run additional commands after pull
            $this->runPostPullCommands();
            
            $this->logWebhookSuccess('Bitbucket', 'Git pull completed successfully');
            return response()->json(['message' => 'Git pull completed successfully'], 200);
        } catch (\Exception $e) {
            $this->logWebhookError('Bitbucket', $e);
            return response()->json(['message' => 'Error during git pull', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Generic webhook endpoint (no specific provider)
     */
    public function handleWebhook(Request $request)
    {
        $this->logWebhookRequest('Generic', $request);

        try {
            // Pull the latest changes from git
            $this->gitPull();
            
            // Optionally run additional commands after pull
            $this->runPostPullCommands();
            
            $this->logWebhookSuccess('Generic', 'Git pull completed successfully');
            return response()->json(['message' => 'Git pull completed successfully'], 200);
        } catch (\Exception $e) {
            $this->logWebhookError('Generic', $e);
            return response()->json(['message' => 'Error during git pull', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Execute git pull in the project directory
     */
    private function gitPull()
    {
        $projectPath = base_path();
        $branch = config('app.webhook_branch', 'main'); // Default to 'main', configurable via env
        
        Log::channel('webhook')->info("Starting git pull", [
            'project_path' => $projectPath,
            'branch' => $branch,
            'working_directory' => getcwd(),
        ]);
        
        // Run git pull origin main command (or configured branch)
        $process = new Process(['git', 'pull', 'origin', $branch], $projectPath);
        $process->setTimeout(300); // 5 minutes timeout
        
        $process->run();
        
        // Log output regardless of success/failure
        Log::channel('webhook')->info("Git pull executed", [
            'exit_code' => $process->getExitCode(),
            'branch' => $branch,
            'output' => $process->getOutput(),
            'error_output' => $process->getErrorOutput(),
        ]);
        
        // Also log to Laravel log
        Log::info('Git pull completed', [
            'exit_code' => $process->getExitCode(),
            'branch' => $branch,
            'output' => $process->getOutput(),
            'error_output' => $process->getErrorOutput(),
        ]);
        
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * Run additional commands after git pull (optional)
     * Examples: composer install, npm install, cache clear, etc.
     */
    private function runPostPullCommands()
    {
        try {
            // Clear application cache
            \Artisan::call('optimize:clear');
            Log::channel('webhook')->info("Cache cleared");
            Log::info('Cache cleared successfully');
        } catch (\Exception $e) {
            Log::channel('webhook')->error("Error clearing cache", [
                'error' => $e->getMessage(),
            ]);
        }
        
        // Run database migrations if needed
        // \Artisan::call('migrate', ['--force' => true]);
        
        // Install composer dependencies if needed
        // $this->runCommand(['composer', 'install'], base_path());
        
        // Install npm dependencies if needed
        // $this->runCommand(['npm', 'install'], base_path());
        
        Log::channel('webhook')->info("Post-pull commands executed");
    }

    /**
     * Run a generic command
     */
    private function runCommand(array $command, string $workingDirectory = null)
    {
        $process = new Process($command, $workingDirectory);
        $process->setTimeout(300);
        
        $commandString = implode(' ', $command);
        
        Log::channel('webhook')->info("Running command: {$commandString}");
        
        $process->run();
        
        Log::channel('webhook')->info("Command output", [
            'command' => $commandString,
            'exit_code' => $process->getExitCode(),
            'output' => $process->getOutput(),
            'error_output' => $process->getErrorOutput(),
        ]);
        
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        
        return $process->getOutput();
    }

    /**
     * Verify GitHub webhook signature
     */
    private function verifyGithubSignature(string $payload, ?string $signature): bool
    {
        $secret = config('app.github_webhook_secret');
        
        if (!$secret || !$signature) {
            // If no secret configured, accept all requests (not recommended for production)
            return true;
        }
        
        $hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($hash, $signature);
    }

    /**
     * Log webhook request details
     */
    private function logWebhookRequest(string $provider, Request $request)
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $headers = collect($request->header())->filter(function ($value, $key) {
            return in_array($key, ['x-hub-signature-256', 'x-gitlab-token', 'x-github-delivery', 'x-gitlab-event']);
        })->toArray();
        
        Log::channel('webhook')->info("=== Webhook Request Received ===", [
            'timestamp' => $timestamp,
            'provider' => $provider,
            'method' => $request->method(),
            'url' => $request->url(),
            'ip' => $request->ip(),
            'headers' => $headers,
            'user_agent' => $request->userAgent(),
            'content_type' => $request->header('Content-Type'),
        ]);
    }

    /**
     * Log webhook success
     */
    private function logWebhookSuccess(string $provider, string $message)
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        
        Log::channel('webhook')->info("=== Webhook Success ===", [
            'timestamp' => $timestamp,
            'provider' => $provider,
            'message' => $message,
            'status' => 'success',
        ]);
    }

    /**
     * Log webhook error
     */
    private function logWebhookError(string $provider, $error)
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        
        if ($error instanceof \Exception) {
            $errorMessage = $error->getMessage();
            $errorTrace = $error->getTraceAsString();
        } else {
            $errorMessage = $error;
            $errorTrace = null;
        }
        
        Log::channel('webhook')->error("=== Webhook Error ===", [
            'timestamp' => $timestamp,
            'provider' => $provider,
            'error' => $errorMessage,
            'trace' => $errorTrace,
            'status' => 'error',
        ]);
    }
}
