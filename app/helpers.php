<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('activity_log')) {
    function activity_log(string $action, $model = null, array $data = []): void
    {
        $context = $data;

        if ($model) {
            $context['model_type'] = get_class($model);
            $context['model_id'] = $model->id ?? null;
        }

        try {
            $context['user_id'] = auth()->id();
        } catch (Throwable $e) {
            $context['user_id'] = null;
        }

        Log::info('activity_log: ' . $action, $context);
    }
}
