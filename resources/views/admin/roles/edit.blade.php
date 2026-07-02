@extends('layouts.admin')

@section('content')
    <div class="w-full space-y-4 animate-in slide-in-from-bottom-4 duration-500">
        <!-- Header -->
        <div class="flex items-center justify-between gap-3">
            <div>
                <a href="{{ route('admin.roles.index') }}" class="group inline-flex items-center text-xs font-bold text-muted-foreground hover:text-primary transition-colors mb-1.5">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1 transform group-hover:-translate-x-1 transition-transform"></i>
                    {{ __('Back_to_Protocol') }}
                </a>
                <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Modify_Security_Clearance') }}: <span class="text-primary">{{ $role->display_name }}</span></h1>
            </div>
        </div>

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="bg-card rounded-md p-4 space-y-4 border border-border shadow-sm">
                <!-- Inputs grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Role_System_Name') }} (Slug)</label>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-medium {{ in_array($role->name, ['root', 'admin', 'visitor']) ? 'opacity-60 cursor-not-allowed' : '' }}"
                            {{ in_array($role->name, ['root', 'admin', 'visitor']) ? 'readonly' : '' }}>
                        @error('name') <p class="text-xs text-rose-500 mt-1 pl-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Role_Display_Name') }}</label>
                        <input type="text" name="display_name" value="{{ old('display_name', $role->display_name) }}" required
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-medium">
                        @error('display_name') <p class="text-xs text-rose-500 mt-1 pl-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Sidebar Access Template -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between border-b border-border pb-2">
                        <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Default_Sidebar_Access_Template') }}</h3>
                        <span class="text-[9px] bg-muted px-2 py-0.5 rounded-sm text-muted-foreground font-bold uppercase tracking-widest">Configuration</span>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($sidebars as $sidebar)
                            <div class="space-y-2 group">
                                <label class="flex items-center p-2.5 bg-muted/20 border border-border rounded hover:border-primary/50 hover:bg-muted/40 transition-all cursor-pointer">
                                    <input type="checkbox" name="sidebars[]" value="{{ $sidebar->id }}"
                                        {{ in_array($sidebar->id, $roleSidebars) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary transition shadow-sm">
                                    <div class="ml-3">
                                        <span class="block text-xs font-bold text-foreground uppercase tracking-wide">{{ __($sidebar->name) }}</span>
                                    </div>
                                </label>
                                
                                @if($sidebar->children->isNotEmpty())
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pl-6 border-l border-border ml-4">
                                        @foreach($sidebar->children as $child)
                                            <label class="flex items-center p-2 bg-card border border-border rounded hover:border-primary/40 hover:bg-muted/20 transition-all cursor-pointer">
                                                <input type="checkbox" name="sidebars[]" value="{{ $child->id }}"
                                                    {{ in_array($child->id, $roleSidebars) ? 'checked' : '' }}
                                                    class="w-3.5 h-3.5 rounded-sm border-input bg-background text-primary focus:ring-primary transition">
                                                <div class="ml-2.5 min-w-0">
                                                    <span class="block text-[11px] font-bold text-foreground uppercase tracking-tight truncate">{{ __($child->name) }}</span>
                                                    <span class="block text-[9px] font-mono text-muted-foreground truncate">{{ $child->route_name }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-3 border-t border-border flex flex-col sm:flex-row justify-between items-center gap-3 mt-4">
                    <div>
                        <button type="button" onclick="if(confirm('{{ __('Delete_Confirmation') }}')) document.getElementById('delete-role-form').submit();" 
                            class="inline-flex items-center px-3 py-1.5 bg-destructive/10 text-destructive text-xs font-bold rounded-sm hover:bg-destructive hover:text-destructive-foreground transition shadow-sm">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-1.5"></i>
                            {{ __('Delete_Role') }}
                        </button>
                    </div>
                    
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <a href="{{ route('admin.roles.index') }}" class="flex-1 sm:flex-none text-center px-4 py-1.5 btn-compact-secondary">
                            {{ __('Abort') }}
                        </a>
                        <button type="submit" class="flex-1 sm:flex-none px-4 py-1.5 btn-compact-primary">
                            {{ __('Update_Sequence') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Hidden Delete Form -->
        <form id="delete-role-form" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection
