@extends('layouts.admin')

@section('title', __('MARC Records Export'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ __('MARC Records Export') }}</h1>
            <p class="text-muted mb-0">{{ __('Export MARC bibliographic records to Excel format') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.marc.books.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Books') }}
            </a>
        </div>
    </div>

    <!-- Export Form -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.marc.export.download') }}" method="GET" target="_blank">
                @csrf
                
                <!-- Filters Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">{{ __('Export Filters') }}</h5>
                    </div>
                    
                    <!-- Framework Filter -->
                    <div class="col-md-6 mb-3">
                        <label for="framework_id" class="form-label">{{ __('Cataloging Framework') }}</label>
                        <select name="framework_id" id="framework_id" class="form-select">
                            <option value="">{{ __('All Frameworks') }}</option>
                            @foreach($frameworks as $framework)
                                <option value="{{ $framework->id }}">{{ $framework->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Document Type Filter -->
                    <div class="col-md-6 mb-3">
                        <label for="document_type_id" class="form-label">{{ __('Document Type') }}</label>
                        <select name="document_type_id" id="document_type_id" class="form-select">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach($documentTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">{{ __('Status') }}</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="new">{{ __('New') }}</option>
                            <option value="complete">{{ __('Complete') }}</option>
                            <option value="approved">{{ __('Approved') }}</option>
                            <option value="rejected">{{ __('Rejected') }}</option>
                        </select>
                    </div>
                    
                    <!-- Date Range -->
                    <div class="col-md-6 mb-3">
                        <label for="date_range" class="form-label">{{ __('Date Range') }}</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" name="date_from" id="date_from" class="form-control" 
                                       placeholder="{{ __('From Date') }}">
                            </div>
                            <div class="col-6">
                                <input type="date" name="date_to" id="date_to" class="form-control" 
                                       placeholder="{{ __('To Date') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Export Options -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">{{ __('Export Options') }}</h5>
                    </div>
                    
                    <!-- Include Items -->
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_items" id="include_items" value="1">
                            <label class="form-check-label" for="include_items">
                                {{ __('Include item information (barcodes, locations, statuses)') }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Export Actions -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>{{ __('Export to Excel') }}
                            </button>
                            <a href="{{ route('admin.marc.import.index') }}" class="btn btn-outline-info">
                                <i class="fas fa-upload me-2"></i>{{ __('Import Records') }}
                            </a>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="fas fa-redo me-2"></i>{{ __('Reset Filters') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{ App\Models\BibliographicRecord::count() }}</h5>
                            <p class="card-text mb-0">{{ __('Total Records') }}</p>
                        </div>
                        <div class="fs-2 opacity-50">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{ App\Models\BibliographicRecord::where('status', 'complete')->count() }}</h5>
                            <p class="card-text mb-0">{{ __('Complete Records') }}</p>
                        </div>
                        <div class="fs-2 opacity-50">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{ App\Models\BibliographicRecord::where('status', 'new')->count() }}</h5>
                            <p class="card-text mb-0">{{ __('New Records') }}</p>
                        </div>
                        <div class="fs-2 opacity-50">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{ App\Models\BookItem::count() }}</h5>
                            <p class="card-text mb-0">{{ __('Total Items') }}</p>
                        </div>
                        <div class="fs-2 opacity-50">
                            <i class="fas fa-barcode"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('framework_id').value = '';
    document.getElementById('document_type_id').value = '';
    document.getElementById('status').value = '';
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    document.getElementById('include_items').checked = false;
}
</script>
@endsection
