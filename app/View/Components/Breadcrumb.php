<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

class Breadcrumb extends Component
{
    public array $segments = [];

    public function __construct()
    {
        $this->generateBreadcrumbs();
    }

    private function generateBreadcrumbs()
    {
        $uri = request()->path();
        $pathSegments = explode('/', $uri);
        $currentPath = '';

        // Add "Home" segment
        $this->segments[] = [
            'name' => 'Home',
            'url' => url('/'),
            'is_last' => false
        ];

        foreach ($pathSegments as $index => $segment) {
            if (empty($segment) || is_numeric($segment)) continue;

            $currentPath .= '/' . $segment;
            $isLast = ($index === count($pathSegments) - 1);
            
            // Clean up name: capitalize, replace underscores/hyphens
            $name = ucwords(str_replace(['-', '_'], ' ', $segment));

            $this->segments[] = [
                'name' => $name,
                'url' => url($currentPath),
                'is_last' => $isLast
            ];
        }
        
        // Final check for last segment
        if (!empty($this->segments)) {
            $this->segments[count($this->segments) - 1]['is_last'] = true;
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.breadcrumb');
    }
}
