<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class WebhookController extends Controller
{
    /**
     * Handle GitHub webhook for automatic git pull
     */
    public function handleGithubWebhook(Request $request)
    {
        // Verify webhook signature (optional but recommended)
        $signature = $request->header('X-Hub-Signature-256');
        $payload = $request->getContent();
        
        // If you have a webhook secret, verify it
        if (!$this->verifyGithubSignature($payload, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        try {
            // Pull the latest changes from git
            $this->gitPull();
            
            // Optionally run additional commands after pull
            $this->runPostPullCommands();
            
            return response()->json(['message' => 'Git pull completed successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error during git pull', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle GitLab webhook for automatic git pull
     */
    public function handleGitlabWebhook(Request $request)
    {
        // Verify webhook token if configured
        $token = $request->header('X-Gitlab-Token');
        $webhookSecret = config('app.gitlab_webhook_secret');
        
        if ($webhookSecret && $token !== $webhookSecret) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        try {
            // Pull the latest changes from git
            $this->gitPull();
            
            // Optionally run additional commands after pull
            $this->runPostPullCommands();
            
            return response()->json(['message' => 'Git pull completed successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error during git pull', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle Bitbucket webhook for automatic git pull
     */
    public function handleBitbucketWebhook(Request $request)
    {
        try {
            // Pull the latest changes from git
            $this->gitPull();
            
            // Optionally run additional commands after pull
            $this->runPostPullCommands();
            
            return response()->json(['message' => 'Git pull completed successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error during git pull', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Generic webhook endpoint (no specific provider)
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Pull the latest changes from git
            $this->gitPull();
            
            // Optionally run additional commands after pull
            $this->runPostPullCommands();
            
            return response()->json(['message' => 'Git pull completed successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error during git pull', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Execute git pull in the project directory
     */
    private function gitPull()
    {
        $projectPath = base_path();
        
        // Run git pull command
        $process = new Process(['git', 'pull'], $projectPath);
        $process->setTimeout(300); // 5 minutes timeout
        
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        
        \Log::info('Git pull executed successfully', [
            'output' => $process->getOutput(),
        ]);
    }

    /**
     * Run additional commands after git pull (optional)
     * Examples: composer install, npm install, cache clear, etc.
     */
    private function runPostPullCommands()
    {
        // Clear application cache
        \Artisan::call('optimize:clear');
        
        // Run database migrations if needed
        // \Artisan::call('migrate', ['--force' => true]);
        
        // Install composer dependencies if needed
        // $this->runCommand(['composer', 'install'], base_path());
        
        // Install npm dependencies if needed
        // $this->runCommand(['npm', 'install'], base_path());
        
        \Log::info('Post-pull commands executed');
    }

    /**
     * Run a generic command
     */
    private function runCommand(array $command, string $workingDirectory = null)
    {
        $process = new Process($command, $workingDirectory);
        $process->setTimeout(300);
        
        $process->run();
        
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
}
