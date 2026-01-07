@extends('layouts.dashboard-main')

@push('css')
<style>
    .preview-container {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 8px;
        text-align: center;
    }
    
    .template-preview {
        margin: 0 auto;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-radius: 8px;
        overflow: hidden;
        position: relative;
    }
    
    .qr-placeholder {
        background: #333;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        position: absolute;
    }
    
    .logo-placeholder {
        background: #666;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8em;
        font-weight: bold;
        position: absolute;
    }
    
    .text-element {
        position: absolute;
        white-space: nowrap;
    }
    
    .template-info {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #666;
    }
    
    .info-value {
        color: #333;
    }
    
    .badge-custom {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75em;
        font-weight: 600;
    }
    
    .actions-toolbar {
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">QR Template Preview</h4>
                        <small class="text-muted">{{ $template->name }} - {{ $template->zone->name ?? 'All Zones' }}</small>
                    </div>
                    <a href="{{ route('admin.qr-template.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Templates
                    </a>
                </div>
                <div class="card-body">
                    <!-- Actions Toolbar -->
                    <div class="actions-toolbar mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge badge-{{ $template->status ? 'success' : 'danger' }} badge-custom">
                                    {{ $template->status ? 'Active' : 'Inactive' }}
                                </span>
                                @if($template->is_default)
                                <span class="badge badge-warning badge-custom">
                                    <i class="fas fa-star"></i> Default Template
                                </span>
                                @endif
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('admin.qr-template.edit', $template->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit Template
                                </a>
                                <button type="button" class="btn btn-outline-info" onclick="generateSampleQR()">
                                    <i class="fas fa-download"></i> Generate Sample
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Template Preview -->
                        <div class="col-lg-8">
                            <div class="preview-container">
                                <h5 class="mb-4">Template Preview</h5>
                                
                                <div class="template-preview position-relative" 
                                     style="width: {{ $template->template_data['canvas']['width'] }}px; 
                                            height: {{ $template->template_data['canvas']['height'] }}px;
                                            @if($template->background_type === 'color')
                                            background-color: {{ $template->background_value }};
                                            @elseif($template->background_type === 'image' && $template->background_url)
                                            background-image: url('{{ $template->background_url }}'); 
                                            background-size: cover; 
                                            background-position: center;
                                            @endif">
                                    
                                    <!-- QR Code Placeholder -->
                                    <div class="qr-placeholder" 
                                         style="width: {{ $template->template_data['qr']['size'] }}px; 
                                                height: {{ $template->template_data['qr']['size'] }}px; 
                                                left: {{ $template->template_data['qr']['position_x'] }}px; 
                                                top: {{ $template->template_data['qr']['position_y'] }}px;">
                                        <i class="fas fa-qrcode fa-3x"></i>
                                    </div>
                                    
                                    <!-- Logo Placeholder -->
                                    @if($template->template_data['logo']['enabled'])
                                    <div class="logo-placeholder" 
                                         style="width: {{ $template->template_data['logo']['size'] }}px; 
                                                height: {{ $template->template_data['logo']['size'] }}px; 
                                                left: {{ $template->template_data['logo']['position_x'] }}px; 
                                                top: {{ $template->template_data['logo']['position_y'] }}px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    @endif
                                    
                                    <!-- Text Elements -->
                                    @if(isset($template->template_data['text_elements']))
                                    @foreach($template->template_data['text_elements'] as $textElement)
                                    <div class="text-element"
                                         style="left: {{ $textElement['position_x'] }}px; 
                                                top: {{ $textElement['position_y'] }}px; 
                                                color: {{ $textElement['color'] }}; 
                                                font-size: {{ $textElement['font_size'] }}px; 
                                                font-family: {{ $textElement['font_family'] ?? 'Arial' }};
                                                font-weight: {{ $textElement['font_weight'] ?? 'normal' }};
                                                text-align: {{ $textElement['alignment'] ?? 'left' }};">
                                        {{ $textElement['text'] }}
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                
                                <div class="mt-4">
                                    <small class="text-muted">
                                        This is a preview of how the QR template will look. 
                                        The actual QR code and logo will replace the placeholders.
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Template Information -->
                        <div class="col-lg-4">
                            <div class="template-info">
                                <h6 class="mb-3 border-bottom pb-2">Template Information</h6>
                                
                                <div class="info-item">
                                    <span class="info-label">Template Name:</span>
                                    <span class="info-value">{{ $template->name }}</span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Zone:</span>
                                    <span class="info-value">{{ $template->zone->name ?? 'All Zones' }}</span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Canvas Size:</span>
                                    <span class="info-value">{{ $template->template_data['canvas']['width'] }}×{{ $template->template_data['canvas']['height'] }}px</span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Background:</span>
                                    <span class="info-value">
                                        @if($template->background_type === 'color')
                                        <span class="d-inline-block rounded" 
                                              style="width: 20px; height: 20px; background-color: {{ $template->background_value }}; border: 1px solid #ddd; vertical-align: middle; margin-right: 5px;"></span>
                                        Color ({{ $template->background_value }})
                                        @else
                                        <i class="fas fa-image"></i> Image
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">QR Code Size:</span>
                                    <span class="info-value">{{ $template->template_data['qr']['size'] }}×{{ $template->template_data['qr']['size'] }}px</span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">QR Position:</span>
                                    <span class="info-value">X:{{ $template->template_data['qr']['position_x'] }}, Y:{{ $template->template_data['qr']['position_y'] }}</span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Error Correction:</span>
                                    <span class="info-value">{{ $template->template_data['qr']['error_correction'] ?? 'M' }} Level</span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Logo Overlay:</span>
                                    <span class="info-value">
                                        @if($template->template_data['logo']['enabled'])
                                        <span class="text-success"><i class="fas fa-check"></i> Enabled</span>
                                        ({{ $template->template_data['logo']['size'] }}px)
                                        @else
                                        <span class="text-muted"><i class="fas fa-times"></i> Disabled</span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Text Elements:</span>
                                    <span class="info-value">{{ isset($template->template_data['text_elements']) ? count($template->template_data['text_elements']) : 0 }}</span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Created By:</span>
                                    <span class="info-value">{{ $template->creator->f_name ?? 'Unknown' }}</span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Created On:</span>
                                    <span class="info-value">{{ $template->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            
                            <!-- Text Elements Details -->
                            @if(isset($template->template_data['text_elements']) && count($template->template_data['text_elements']) > 0)
                            <div class="template-info mt-4">
                                <h6 class="mb-3 border-bottom pb-2">Text Elements</h6>
                                
                                @foreach($template->template_data['text_elements'] as $index => $textElement)
                                <div class="mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>Element {{ $index + 1 }}</strong>
                                        <span class="badge badge-light">{{ $textElement['font_size'] }}px</span>
                                    </div>
                                    
                                    <p class="mb-2" style="color: {{ $textElement['color'] }}; font-family: {{ $textElement['font_family'] ?? 'Arial' }};">
                                        "{{ $textElement['text'] }}"
                                    </p>
                                    
                                    <small class="text-muted">
                                        Position: X:{{ $textElement['position_x'] }}, Y:{{ $textElement['position_y'] }}<br>
                                        Font: {{ $textElement['font_family'] ?? 'Arial' }} ({{ $textElement['font_weight'] ?? 'normal' }})
                                    </small>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
    function generateSampleQR() {
        // This would typically make an API call to generate a sample QR with this template
        toastr.info('Sample QR generation feature will be implemented with FFmpeg integration');
    }
</script>
@endpush
