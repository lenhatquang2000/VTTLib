@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('New OER Resource') }}</h1>
            <p class="text-sm text-muted-foreground">{{ __('Create a new open educational resource.') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.oer.index') }}" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-muted text-muted-foreground hover:bg-muted/80 border border-border hover:text-foreground active:bg-muted/60">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span class="hidden sm:inline">{{ __('Back') }}</span>
            </a>
        </div>
    </div>

    <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <form action="{{ route('admin.oer.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Basic Information -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-primary"></i>
                    {{ __('Basic Information') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1 sm:col-span-2">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Title') }} <span class="text-destructive">*</span></label>
                        <input type="text" name="title" required 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Resource Type') }} <span class="text-destructive">*</span></label>
                        <select name="resource_type" required 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            <option value="">{{ __('Select Type') }}</option>
                            <option value="document">Document</option>
                            <option value="video">Video</option>
                            <option value="audio">Audio</option>
                            <option value="interactive">Interactive</option>
                            <option value="dataset">Dataset</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Language') }}</label>
                        <input type="text" name="language" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="e.g., en, vi, fr">
                    </div>
                    <div class="space-y-1 sm:col-span-2">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Description') }}</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Author & Publisher -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-primary"></i>
                    {{ __('Author & Publisher') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Authors') }}</label>
                        <input type="text" name="authors[]" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="Author name">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Publisher') }}</label>
                        <input type="text" name="publisher" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Publish Year') }}</label>
                        <input type="number" name="publish_year" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="2024">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Format') }}</label>
                        <input type="text" name="format" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="e.g., PDF, MP4">
                    </div>
                </div>
            </div>

            <!-- Classification -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="tag" class="w-4 h-4 text-primary"></i>
                    {{ __('Classification') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Subjects') }}</label>
                        <input type="text" name="subjects[]" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="Subject area">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Educational Levels') }}</label>
                        <input type="text" name="educational_levels[]" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="e.g., undergraduate, graduate">
                    </div>
                    <div class="space-y-1 sm:col-span-2">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Keywords') }}</label>
                        <input type="text" name="keywords" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="Comma-separated keywords">
                    </div>
                </div>
            </div>

            <!-- License & Rights -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4 text-primary"></i>
                    {{ __('License & Rights') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('License') }}</label>
                        <select name="license" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            <option value="">{{ __('Select License') }}</option>
                            <option value="CC BY">CC BY (Attribution)</option>
                            <option value="CC BY-SA">CC BY-SA (Attribution-ShareAlike)</option>
                            <option value="CC BY-ND">CC BY-ND (Attribution-NoDerivs)</option>
                            <option value="CC BY-NC">CC BY-NC (Attribution-NonCommercial)</option>
                            <option value="CC BY-NC-SA">CC BY-NC-SA (Attribution-NonCommercial-ShareAlike)</option>
                            <option value="CC BY-NC-ND">CC BY-NC-ND (Attribution-NonCommercial-NoDerivs)</option>
                            <option value="Public Domain">Public Domain</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('License URL') }}</label>
                        <input type="url" name="license_url" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="https://...">
                    </div>
                </div>
            </div>

            <!-- Files -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="upload" class="w-4 h-4 text-primary"></i>
                    {{ __('Files') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Resource File') }}</label>
                        <input type="file" name="file" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all file:mr-2 file:py-1 file:px-2 file:rounded-sm file:border-0 file:text-xs file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        <p class="text-[10px] text-muted-foreground mt-1">Max 100MB</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Cover Image') }}</label>
                        <input type="file" name="cover" accept="image/*"
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all file:mr-2 file:py-1 file:px-2 file:rounded-sm file:border-0 file:text-xs file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        <p class="text-[10px] text-muted-foreground mt-1">Max 10MB</p>
                    </div>
                </div>
            </div>

            <!-- External Links -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="link" class="w-4 h-4 text-primary"></i>
                    {{ __('External Links') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('External Link') }}</label>
                        <input type="url" name="external_link" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="https://...">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Source') }}</label>
                        <input type="text" name="source" 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                            placeholder="Source name">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="p-4 border-b border-border">
                <h3 class="text-sm font-bold text-foreground mb-3 flex items-center gap-2">
                    <i data-lucide="settings" class="w-4 h-4 text-primary"></i>
                    {{ __('Status') }}
                </h3>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Publication Status') }} <span class="text-destructive">*</span></label>
                    <select name="status" required 
                        class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        <option value="draft">{{ __('Draft') }}</option>
                        <option value="published">{{ __('Published') }}</option>
                    </select>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-4 bg-muted/30 flex flex-col sm:flex-row justify-end gap-2">
                <a href="{{ route('admin.oer.index') }}" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-muted text-muted-foreground hover:bg-muted/80 border border-border hover:text-foreground active:bg-muted/60">
                    {{ __('Cancel') }}
                </a>
                <button type="button" onclick="generateMetadata()" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-amber-500/10 text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 border border-amber-500/20 hover:border-amber-500/40 active:bg-amber-500/15">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">{{ __('Auto Generate') }}</span>
                    <span class="sm:hidden">{{ __('Generate') }}</span>
                </button>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm active:bg-primary/80">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">{{ __('Create Resource') }}</span>
                    <span class="sm:hidden">{{ __('Create') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });

    function generateMetadata() {
        // Generate sample data for quick creation
        const sampleData = generateSampleResource();

        // Fill all form fields
        document.querySelector('input[name="title"]').value = sampleData.title;
        document.querySelector('select[name="resource_type"]').value = sampleData.resource_type;
        document.querySelector('input[name="language"]').value = sampleData.language;
        document.querySelector('textarea[name="description"]').value = sampleData.description;
        
        // Authors
        const authorsInputs = document.querySelectorAll('input[name="authors[]"]');
        if (authorsInputs.length > 0) {
            authorsInputs[0].value = sampleData.author;
        }
        
        document.querySelector('input[name="publisher"]').value = sampleData.publisher;
        document.querySelector('input[name="publish_year"]').value = sampleData.publish_year;
        document.querySelector('input[name="format"]').value = sampleData.format;
        
        // Subjects
        const subjectsInputs = document.querySelectorAll('input[name="subjects[]"]');
        if (subjectsInputs.length > 0) {
            subjectsInputs[0].value = sampleData.subject;
        }
        
        // Educational Levels
        const levelsInputs = document.querySelectorAll('input[name="educational_levels[]"]');
        if (levelsInputs.length > 0) {
            levelsInputs[0].value = sampleData.educational_level;
        }
        
        document.querySelector('input[name="keywords"]').value = sampleData.keywords;
        
        // License
        document.querySelector('select[name="license"]').value = sampleData.license;
        document.querySelector('input[name="license_url"]').value = sampleData.license_url;
        
        // Source & External Link
        document.querySelector('input[name="source"]').value = sampleData.source;
        document.querySelector('input[name="external_link"]').value = sampleData.external_link;
        
        // Status
        document.querySelector('select[name="status"]').value = sampleData.status;

        // Show success feedback
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i><span class="hidden sm:inline">{{ __("Generated!") }}</span><span class="sm:hidden">{{ __("Done") }}</span>';
        btn.classList.add('bg-green-500/10', 'text-green-600', 'dark:text-green-400');
        lucide.createIcons();
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-green-500/10', 'text-green-600', 'dark:text-green-400');
            lucide.createIcons();
        }, 2000);
    }

    function generateSampleResource() {
        const titles = [
            'Introduction to Mathematics',
            'Python Programming Fundamentals',
            'World History Overview',
            'Environmental Science Basics',
            'English Literature Essentials',
            'Business Management Principles',
            'Chemistry Lab Manual',
            'Digital Marketing Guide',
            'Web Development Bootcamp',
            'Educational Psychology',
        ];

        const types = ['document', 'video', 'audio', 'interactive', 'dataset'];
        const authors = ['Dr. John Smith', 'Prof. Jane Doe', 'Educational Team', 'Learning Institute', 'Knowledge Base'];
        const publishers = ['Academic Press', 'Learning Platforms Inc.', 'Open Education Foundation', 'University Press', 'Global Learning'];
        const subjects = ['Education', 'Technology', 'Science', 'Business', 'Arts', 'Mathematics', 'History'];
        const levels = ['undergraduate', 'graduate', 'professional', 'general', 'advanced'];
        const sources = ['Open Educational Resources', 'Creative Commons', 'Public Domain', 'Educational Database', 'Learning Repository'];

        // Random selection
        const title = titles[Math.floor(Math.random() * titles.length)];
        const resourceType = types[Math.floor(Math.random() * types.length)];
        const author = authors[Math.floor(Math.random() * authors.length)];
        const publisher = publishers[Math.floor(Math.random() * publishers.length)];
        const subject = subjects[Math.floor(Math.random() * subjects.length)];
        const level = levels[Math.floor(Math.random() * levels.length)];
        const source = sources[Math.floor(Math.random() * sources.length)];

        const description = `This is a comprehensive open educational resource on ${title.toLowerCase()}. It provides learners with detailed information, practical examples, and interactive content designed to enhance understanding and skill development. Suitable for both beginners and intermediate learners seeking quality educational materials.`;

        const keywords = `${title.toLowerCase().split(' ').slice(0, 3).join(', ')}, education, learning, open resource, ${subject.toLowerCase()}`;

        return {
            title: title,
            resource_type: resourceType,
            language: 'en',
            description: description,
            author: author,
            publisher: publisher,
            publish_year: new Date().getFullYear(),
            format: resourceType === 'video' ? 'MP4' : (resourceType === 'audio' ? 'MP3' : 'PDF'),
            subject: subject,
            educational_level: level,
            keywords: keywords,
            license: 'CC BY',
            license_url: 'https://creativecommons.org/licenses/by/4.0/',
            source: source,
            external_link: 'https://example.com/resource',
            status: 'draft',
        };
    }
</script>
@endsection
