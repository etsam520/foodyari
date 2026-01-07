@extends('layouts.dashboard-main')

@push('css')
<style>
    .template-designer {
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #f8f9fa;
        padding: 20px;
        min-height: 500px;
        position: relative;
        overflow: hidden;
    }
    
    .canvas-container {
        position: relative;
        margin: 0 auto;
        border: 2px dashed #007bff;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .draggable-element {
        position: absolute;
        cursor: move;
        border: 1px dashed transparent;
        padding: 2px;
    }
    
    .draggable-element:hover {
        border-color: #007bff;
    }
    
    .draggable-element.selected {
        border-color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
    }
    
    .qr-placeholder {
        background: #333;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8em;
    }
    
    .logo-placeholder {
        background: #666;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7em;
    }
    
    .text-element {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 2px;
    }
    
    .control-panel {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        max-height: 600px;
        overflow-y: auto;
    }
    
    .element-controls {
        display: none;
    }
    
    .element-controls.active {
        display: block;
    }
    
    .color-input {
        width: 50px;
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
    
    .icon-selector {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 8px;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 4px;
    }
    
    .icon-option {
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .icon-option:hover {
        background: #e9ecef;
    }
    
    .icon-option.selected {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }
    
    .icon-option img {
        max-width: 40px;
        max-height: 40px;
        object-fit: contain;
    }
    
    .background-image-preview {
        max-width: 100%;
        max-height: 150px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 10px;
    }
    
    .text-preset-selector {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin-bottom: 15px;
    }
    
    .text-preset {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        font-size: 12px;
    }
    
    .text-preset:hover {
        background: #e9ecef;
    }
    
    .text-preset.selected {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }
    
    .custom-icon-upload {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        margin-bottom: 15px;
        transition: all 0.2s;
    }
    
    .custom-icon-upload:hover {
        border-color: #007bff;
        background: #f8f9ff;
    }
    
    .custom-icon-upload.dragover {
        border-color: #007bff;
        background: #e3f2fd;
    }
    
    .uploaded-icons-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-top: 15px;
    }
    
    .uploaded-icon {
        position: relative;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .uploaded-icon:hover {
        border-color: #007bff;
    }
    
    .uploaded-icon.selected {
        border-color: #007bff;
        background: #f8f9ff;
    }
    
    .uploaded-icon img {
        width: 100%;
        height: 50px;
        object-fit: contain;
    }
    
    .uploaded-icon .remove-icon {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        cursor: pointer;
    }
    
    .image-icon-element {
        position: absolute;
        cursor: move;
        border: 2px dashed transparent;
        border-radius: 4px;
        transition: all 0.2s;
    }
    
    .image-icon-element:hover {
        border-color: #007bff;
    }
    
    .image-icon-element.selected {
        border-color: #dc3545;
        box-shadow: 0 0 10px rgba(220, 53, 69, 0.3);
    }
    
    .image-icon-element img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 2px;
    }
    
    .image-icon-control {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f8f9fa;
        transition: all 0.2s;
    }
    
    .image-icon-control:hover {
        border-color: #007bff;
        background: #f0f8ff;
    }
    
    .image-icon-control.active {
        border-color: #007bff;
        background: #e3f2fd;
    }
    
    .image-icon-preview {
        width: 60px;
        height: 60px;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
        display: inline-block;
        margin-right: 15px;
        float: left;
    }
    
    .image-icon-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .uploaded-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
        margin-top: 15px;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
    }
    
    .uploaded-image-item {
        position: relative;
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s;
        aspect-ratio: 1;
    }
    
    .uploaded-image-item:hover {
        border-color: #007bff;
        transform: scale(1.05);
    }
    
    .uploaded-image-item.selected {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
    }
    
    .uploaded-image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .uploaded-image-item .remove-image {
        position: absolute;
        top: 2px;
        right: 2px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        cursor: pointer;
        display: none;
    }
    
    .uploaded-image-item:hover .remove-image {
        display: block;
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
                        <h4 class="card-title">
                            {{ isset($template) ? 'Edit QR Template' : 'Create QR Template' }}
                        </h4>
                        <small class="text-muted">
                            {{ isset($template) ? 'Modify your QR code template design' : 'Design a custom QR code template for your zone' }}
                        </small>
                    </div>
                    <a href="{{ route('admin.qr-template.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Templates
                    </a>
                </div>
                <div class="card-body">
                    <form id="templateForm" 
                          action="{{ isset($template) ? route('admin.qr-template.update', $template->id) : route('admin.qr-template.store') }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($template))
                            @method('POST')
                        @endif
                        <div class="row">
                            <!-- Left Panel - Template Designer -->
                            <div class="col-lg-8">
                                <div class="template-designer">
                                    <div class="text-center mb-3">
                                        <h6>Template Preview</h6>
                                        <small class="text-muted">Drag elements to reposition them</small>
                                    </div>
                                    
                                    <div id="canvasContainer" class="canvas-container" 
                                         style="width: 400px; height: 400px;">
                                        <!-- QR Code Placeholder -->
                                        <div id="qrElement" class="draggable-element qr-placeholder" 
                                             style="width: 150px; height: 150px; left: 125px; top: 125px;">
                                            QR CODE
                                        </div>
                                        
                                        <!-- Logo Placeholder -->
                                        <div id="logoElement" class="draggable-element logo-placeholder" 
                                             style="width: 50px; height: 50px; left: 200px; top: 200px; display: none;">
                                            LOGO
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Panel - Controls -->
                            <div class="col-lg-4">
                                <div class="control-panel">
                                    <!-- Basic Settings -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">Basic Settings</h6>
                                        
                                        <div class="form-group mb-3">
                                            <label>Template Name</label>
                                            <input type="text" name="name" class="form-control" 
                                                   placeholder="Enter template name" 
                                                   value="{{ isset($template) ? $template->name : old('name') }}" required>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label>Zone</label>
                                            <select name="zone_id" class="form-control" required>
                                                <option value="">Select Zone</option>
                                                @foreach($zones as $zone)
                                                <option value="{{ $zone->id }}" 
                                                        {{ (isset($template) && $template->zone_id == $zone->id) || old('zone_id') == $zone->id ? 'selected' : '' }}>
                                                    {{ $zone->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="is_default" id="isDefault"
                                                   {{ (isset($template) && $template->is_default) || old('is_default') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isDefault">
                                                Set as default template for this zone
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Canvas Settings -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">Canvas Settings</h6>
                                        
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label>Width (px)</label>
                                                    <input type="number" name="template_width" id="canvasWidth" 
                                                           class="form-control" 
                                                           value="{{ isset($template) ? $template->template_data['canvas']['width'] ?? 400 : (old('template_width') ?? 400) }}" 
                                                           min="200" max="2000" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label>Height (px)</label>
                                                    <input type="number" name="template_height" id="canvasHeight" 
                                                           class="form-control" 
                                                           value="{{ isset($template) ? $template->template_data['canvas']['height'] ?? 400 : (old('template_height') ?? 400) }}" 
                                                           min="200" max="2000" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Background Settings -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">Background</h6>
                                        
                                        <div class="form-group mb-3">
                                            <label>Background Type</label>
                                            <select name="background_type" id="backgroundType" class="form-control" required>
                                                <option value="color" 
                                                        {{ (isset($template) && $template->background_type == 'color') || old('background_type') == 'color' ? 'selected' : '' }}>
                                                    Solid Color
                                                </option>
                                                <option value="image" 
                                                        {{ (isset($template) && $template->background_type == 'image') || old('background_type') == 'image' ? 'selected' : '' }}>
                                                    Image
                                                </option>
                                            </select>
                                        </div>
                                        
                                        <div id="colorBackground" class="form-group mb-3" 
                                             style="display: {{ (isset($template) && $template->background_type == 'image') ? 'none' : 'block' }};">
                                            <label>Background Color</label>
                                            <div class="d-flex align-items-center">
                                                <input type="color" name="background_color" id="bgColor" 
                                                       class="color-input me-2" 
                                                       value="{{ isset($template) && $template->background_type == 'color' ? $template->background_value : (old('background_color') ?? '#ffffff') }}">
                                                <input type="text" class="form-control" id="bgColorText" 
                                                       value="{{ isset($template) && $template->background_type == 'color' ? $template->background_value : (old('background_color') ?? '#ffffff') }}" readonly>
                                            </div>
                                        </div>
                                        
                                        <div id="imageBackground" class="form-group mb-3" style="display: none;">
                                            <label>Background Image</label>
                                            <div class="custom-icon-upload" id="backgroundImageUpload">
                                                <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-muted"></i>
                                                <p class="mb-2">Click to upload or drag & drop</p>
                                                <small class="text-muted">JPG, PNG, GIF up to 5MB</small>
                                                <input type="file" name="background_image" class="d-none" 
                                                       accept="image/*" id="bgImage">
                                            </div>
                                            <div id="backgroundImagePreview" style="display: none;">
                                                <img id="backgroundPreviewImg" class="background-image-preview">
                                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" 
                                                        onclick="clearBackgroundImage()">
                                                    <i class="fas fa-trash"></i> Remove Image
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- QR Code Settings -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">QR Code Settings</h6>
                                        
                                        <div class="form-group mb-3">
                                            <label>QR Size (px)</label>
                                            <input type="number" name="qr_size" id="qrSize" class="form-control" 
                                                   value="{{ isset($template) ? $template->template_data['qr']['size'] ?? 150 : (old('qr_size') ?? 150) }}" 
                                                   min="50" max="500" required>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label>X Position</label>
                                                    <input type="number" name="qr_position_x" id="qrPosX" 
                                                           class="form-control" 
                                                           value="{{ isset($template) ? $template->template_data['qr']['position_x'] ?? 125 : (old('qr_position_x') ?? 125) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label>Y Position</label>
                                                    <input type="number" name="qr_position_y" id="qrPosY" 
                                                           class="form-control" 
                                                           value="{{ isset($template) ? $template->template_data['qr']['position_y'] ?? 125 : (old('qr_position_y') ?? 125) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @php
                                            $errorCorrection = isset($template) ? $template->template_data['qr']['error_correction'] ?? 'M' : (old('qr_error_correction') ?? 'M');
                                        @endphp
                                        <div class="form-group mb-3">
                                            <label>Error Correction</label>
                                            <select name="qr_error_correction" class="form-control">
                                                <option value="L" {{ $errorCorrection == 'L' ? 'selected' : '' }}>Low (7%)</option>
                                                <option value="M" {{ $errorCorrection == 'M' ? 'selected' : '' }}>Medium (15%)</option>
                                                <option value="Q" {{ $errorCorrection == 'Q' ? 'selected' : '' }}>Quartile (25%)</option>
                                                <option value="H" {{ $errorCorrection == 'H' ? 'selected' : '' }}>High (30%)</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Image Icons Settings -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">
                                            Image Icons
                                            <button type="button" class="btn btn-sm btn-outline-primary float-end" 
                                                    onclick="addImageIcon()">
                                                <i class="fas fa-plus"></i> Add Image Icon
                                            </button>
                                        </h6>
                                        
                                        <div class="form-group mb-3">
                                            <label>Upload Multiple Images as Icons</label>
                                            <div class="custom-icon-upload" id="multiImageUploadArea">
                                                <i class="fas fa-images fa-2x mb-2 text-muted"></i>
                                                <p class="mb-2">Upload Multiple Images (PNG, JPG, GIF)</p>
                                                <small class="text-muted">Each image up to 3MB, drag & drop supported</small>
                                                <input type="file" name="image_icons[]" class="d-none" 
                                                       accept="image/*" id="multiImageInput" multiple>
                                            </div>
                                            <div id="uploadedImagesGrid" class="uploaded-icons-grid mt-3"></div>
                                        </div>
                                        
                                        <div id="imageIconElements" class="mb-3">
                                            <!-- Image icon elements will be added dynamically -->
                                        </div>
                                    </div>
                                    
                                    <!-- Logo Settings (Legacy) -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">Single Logo Settings</h6>
                                        
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="logo_enabled" id="logoEnabled">
                                            <label class="form-check-label" for="logoEnabled">
                                                Enable Single Logo Overlay
                                            </label>
                                        </div>
                                        
                                        <div id="logoControls" style="display: none;">
                                            <div class="form-group mb-3">
                                                <label>Logo/Icon Source</label>
                                                <select id="logoSource" class="form-control mb-3">
                                                    <option value="upload">Upload Custom Icon (PNG)</option>
                                                    <option value="preset">Choose from Presets</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Custom Icon Upload -->
                                            <div id="customIconUpload" class="form-group mb-3">
                                                <div class="custom-icon-upload" id="iconUploadArea">
                                                    <i class="fas fa-image fa-2x mb-2 text-muted"></i>
                                                    <p class="mb-2">Upload PNG Icon</p>
                                                    <small class="text-muted">PNG files only, up to 2MB</small>
                                                    <input type="file" name="custom_icons[]" class="d-none" 
                                                           accept=".png" id="customIconInput" multiple>
                                                </div>
                                                <div id="uploadedIconsGrid" class="uploaded-icons-grid"></div>
                                            </div>
                                            
                                            <!-- Preset Icons -->
                                            <div id="presetIcons" class="form-group mb-3" style="display: none;">
                                                <label>Select Preset Icon</label>
                                                <div class="icon-selector">
                                                    <div class="icon-option" data-icon="restaurant">
                                                        <i class="fas fa-utensils fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="delivery">
                                                        <i class="fas fa-truck fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="phone">
                                                        <i class="fas fa-phone fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="location">
                                                        <i class="fas fa-map-marker-alt fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="star">
                                                        <i class="fas fa-star fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="heart">
                                                        <i class="fas fa-heart fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="award">
                                                        <i class="fas fa-award fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="clock">
                                                        <i class="fas fa-clock fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="wifi">
                                                        <i class="fas fa-wifi fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="coffee">
                                                        <i class="fas fa-coffee fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="leaf">
                                                        <i class="fas fa-leaf fa-2x"></i>
                                                    </div>
                                                    <div class="icon-option" data-icon="shield">
                                                        <i class="fas fa-shield-alt fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mb-3">
                                                <label>Logo Size (px)</label>
                                                <input type="number" name="logo_size" id="logoSize" 
                                                       class="form-control" value="50" min="10" max="200">
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>X Position</label>
                                                        <input type="number" name="logo_position_x" id="logoPosX" 
                                                               class="form-control" value="200">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Y Position</label>
                                                        <input type="number" name="logo_position_y" id="logoPosY" 
                                                               class="form-control" value="200">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Text Elements -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">
                                            Text Elements
                                            <button type="button" class="btn btn-sm btn-outline-primary float-end" 
                                                    onclick="addTextElement()">
                                                <i class="fas fa-plus"></i> Add Text
                                            </button>
                                        </h6>
                                        
                                        <!-- Text Presets -->
                                        <div class="form-group mb-3">
                                            <label>Quick Text Presets</label>
                                            <div class="text-preset-selector">
                                                <div class="text-preset" data-preset="welcome">
                                                    <strong>Welcome</strong><br>
                                                    <small>Welcome Message</small>
                                                </div>
                                                <div class="text-preset" data-preset="menu">
                                                    <strong>Menu</strong><br>
                                                    <small>Scan for Menu</small>
                                                </div>
                                                <div class="text-preset" data-preset="contact">
                                                    <strong>Contact</strong><br>
                                                    <small>Contact Info</small>
                                                </div>
                                                <div class="text-preset" data-preset="offers">
                                                    <strong>Offers</strong><br>
                                                    <small>Special Deals</small>
                                                </div>
                                                <div class="text-preset" data-preset="hours">
                                                    <strong>Hours</strong><br>
                                                    <small>Opening Hours</small>
                                                </div>
                                                <div class="text-preset" data-preset="custom">
                                                    <strong>Custom</strong><br>
                                                    <small>Your Text</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="textElements">
                                            <!-- Text elements will be added dynamically -->
                                        </div>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> 
                                            {{ isset($template) ? 'Update Template' : 'Create Template' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
    const assetPath = document.querySelector('meta[name="asset-path"]').getAttribute('content') || '';
    const basePath = document.querySelector('meta[name="base-path"]').getAttribute('content') || '';
    let textElementCount = 0;
    let imageIconCount = 0;
    let uploadedIcons = [];
    let uploadedImages = [];
    let selectedIcon = null;
    
    // Canvas dimension handlers
    document.getElementById('canvasWidth').addEventListener('input', function() {
        updateCanvasSize();
    });
    
    document.getElementById('canvasHeight').addEventListener('input', function() {
        updateCanvasSize();
    });
    
    // Background type handler
    document.getElementById('backgroundType').addEventListener('change', function() {
        const type = this.value;
        document.getElementById('colorBackground').style.display = type === 'color' ? 'block' : 'none';
        document.getElementById('imageBackground').style.display = type === 'image' ? 'block' : 'none';
        
        if (type === 'color') {
            updateBackgroundColor();
        }
    });
    
    // Background color handler
    document.getElementById('bgColor').addEventListener('input', function() {
        document.getElementById('bgColorText').value = this.value;
        updateBackgroundColor();
    });
    
    // Background image upload area handler
    document.getElementById('backgroundImageUpload').addEventListener('click', function() {
        document.getElementById('bgImage').click();
    });
    
    // Background image drag and drop
    const bgUploadArea = document.getElementById('backgroundImageUpload');
    bgUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    bgUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    bgUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleBackgroundImageUpload(files[0]);
        }
    });
    
    // Background image handler
    document.getElementById('bgImage').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            handleBackgroundImageUpload(this.files[0]);
        }
    });
    
    // QR settings handlers
    document.getElementById('qrSize').addEventListener('input', function() {
        updateQRSize();
    });
    
    document.getElementById('qrPosX').addEventListener('input', function() {
        updateQRPosition();
    });
    
    document.getElementById('qrPosY').addEventListener('input', function() {
        updateQRPosition();
    });
    
    // Logo handlers
    document.getElementById('logoEnabled').addEventListener('change', function() {
        const enabled = this.checked;
        document.getElementById('logoControls').style.display = enabled ? 'block' : 'none';
        document.getElementById('logoElement').style.display = enabled ? 'block' : 'none';
        
        if (enabled) {
            updateLogoSettings();
        }
    });
    
    document.getElementById('logoSize').addEventListener('input', function() {
        updateLogoSettings();
    });
    
    document.getElementById('logoPosX').addEventListener('input', function() {
        updateLogoSettings();
    });
    
    document.getElementById('logoPosY').addEventListener('input', function() {
        updateLogoSettings();
    });
    
    // Logo source handler
    document.getElementById('logoSource').addEventListener('change', function() {
        const source = this.value;
        document.getElementById('customIconUpload').style.display = source === 'upload' ? 'block' : 'none';
        document.getElementById('presetIcons').style.display = source === 'preset' ? 'block' : 'none';
    });
    
    // Custom icon upload handler
    document.getElementById('iconUploadArea').addEventListener('click', function() {
        document.getElementById('customIconInput').click();
    });
    
    // Icon drag and drop
    const iconUploadArea = document.getElementById('iconUploadArea');
    iconUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    iconUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    iconUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleIconUpload(Array.from(files));
    });
    
    document.getElementById('customIconInput').addEventListener('change', function() {
        if (this.files.length > 0) {
            handleIconUpload(Array.from(this.files));
        }
    });
    
    // Preset icon selection
    document.querySelectorAll('.icon-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.icon-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            selectedIcon = {
                type: 'preset',
                icon: this.dataset.icon,
                html: this.innerHTML
            };
            updateLogoElement();
        });
    });
    
    // Text preset selection
    document.querySelectorAll('.text-preset').forEach(preset => {
        preset.addEventListener('click', function() {
            const presetType = this.dataset.preset;
            const presetTexts = {
                welcome: 'Welcome to Our Restaurant!',
                menu: 'Scan to View Menu',
                contact: 'Contact Us',
                offers: 'Special Offers Available',
                hours: 'Open Daily 9AM - 10PM',
                custom: 'Enter your text'
            };
            
            if (presetTexts[presetType]) {
                addTextElement(presetTexts[presetType]);
            }
        });
    });
    
    // Multiple image upload handler
    document.getElementById('multiImageUploadArea').addEventListener('click', function() {
        document.getElementById('multiImageInput').click();
    });
    
    // Multi-image drag and drop
    const multiImageUploadArea = document.getElementById('multiImageUploadArea');
    multiImageUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    multiImageUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    multiImageUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleMultipleImageUpload(Array.from(files));
    });
    
    document.getElementById('multiImageInput').addEventListener('change', function() {
        if (this.files.length > 0) {
            handleMultipleImageUpload(Array.from(this.files));
        }
    });
    
    // Functions
    function updateCanvasSize() {
        const width = document.getElementById('canvasWidth').value;
        const height = document.getElementById('canvasHeight').value;
        const canvas = document.getElementById('canvasContainer');
        
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';
    }
    
    function updateBackgroundColor() {
        const color = document.getElementById('bgColor').value;
        const canvas = document.getElementById('canvasContainer');
        canvas.style.backgroundColor = color;
        canvas.style.backgroundImage = 'none';
    }
    
    function updateQRSize() {
        const size = document.getElementById('qrSize').value;
        const qrElement = document.getElementById('qrElement');
        qrElement.style.width = size + 'px';
        qrElement.style.height = size + 'px';
    }
    
    function updateQRPosition() {
        const x = document.getElementById('qrPosX').value;
        const y = document.getElementById('qrPosY').value;
        const qrElement = document.getElementById('qrElement');
        qrElement.style.left = x + 'px';
        qrElement.style.top = y + 'px';
    }
    
    function updateLogoSettings() {
        const size = document.getElementById('logoSize').value;
        const x = document.getElementById('logoPosX').value;
        const y = document.getElementById('logoPosY').value;
        const logoElement = document.getElementById('logoElement');
        
        logoElement.style.width = size + 'px';
        logoElement.style.height = size + 'px';
        logoElement.style.left = x + 'px';
        logoElement.style.top = y + 'px';
        
        updateLogoElement();
    }
    
    function handleBackgroundImageUpload(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const canvas = document.getElementById('canvasContainer');
                canvas.style.backgroundImage = `url(${e.target.result})`;
                canvas.style.backgroundSize = 'cover';
                canvas.style.backgroundPosition = 'center';
                
                // Show preview
                document.getElementById('backgroundPreviewImg').src = e.target.result;
                document.getElementById('backgroundImagePreview').style.display = 'block';
                document.getElementById('backgroundImageUpload').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }
    
    function clearBackgroundImage() {
        const canvas = document.getElementById('canvasContainer');
        canvas.style.backgroundImage = 'none';
        document.getElementById('backgroundImagePreview').style.display = 'none';
        document.getElementById('backgroundImageUpload').style.display = 'block';
        document.getElementById('bgImage').value = '';
    }
    
    function handleIconUpload(files) {
        const validFiles = files.filter(file => file.type === 'image/png' && file.size <= 2 * 1024 * 1024);
        
        validFiles.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const iconData = {
                    id: Date.now() + Math.random(),
                    name: file.name,
                    dataUrl: e.target.result,
                    type: 'custom'
                };
                
                uploadedIcons.push(iconData);
                addIconToGrid(iconData);
            };
            reader.readAsDataURL(file);
        });
        
        if (validFiles.length !== files.length) {
            alert('Some files were skipped. Only PNG files under 2MB are allowed.');
        }
    }
    
    function addIconToGrid(iconData) {
        const grid = document.getElementById('uploadedIconsGrid');
        const iconDiv = document.createElement('div');
        iconDiv.className = 'uploaded-icon';
        iconDiv.dataset.iconId = iconData.id;
        
        iconDiv.innerHTML = `
            <img src="${iconData.dataUrl}" alt="${iconData.name}">
            <button type="button" class="remove-icon" onclick="removeUploadedIcon('${iconData.id}')">×</button>
        `;
        
        iconDiv.addEventListener('click', function(e) {
            if (!e.target.classList.contains('remove-icon')) {
                document.querySelectorAll('.uploaded-icon').forEach(icon => icon.classList.remove('selected'));
                this.classList.add('selected');
                selectedIcon = {
                    type: 'custom',
                    id: iconData.id,
                    dataUrl: iconData.dataUrl
                };
                updateLogoElement();
            }
        });
        
        grid.appendChild(iconDiv);
    }
    
    function removeUploadedIcon(iconId) {
        uploadedIcons = uploadedIcons.filter(icon => icon.id !== iconId);
        document.querySelector(`[data-icon-id="${iconId}"]`).remove();
        
        if (selectedIcon && selectedIcon.id === iconId) {
            selectedIcon = null;
            updateLogoElement();
        }
    }
    
    function updateLogoElement() {
        const logoElement = document.getElementById('logoElement');
        
        if (selectedIcon) {
            if (selectedIcon.type === 'preset') {
                logoElement.innerHTML = selectedIcon.html;
                logoElement.style.display = 'flex';
                logoElement.style.alignItems = 'center';
                logoElement.style.justifyContent = 'center';
            } else if (selectedIcon.type === 'custom') {
                logoElement.innerHTML = `<img src="${selectedIcon.dataUrl}" style="width: 100%; height: 100%; object-fit: contain;">`;
                logoElement.style.display = 'block';
            }
        } else {
            logoElement.innerHTML = 'LOGO';
            logoElement.style.display = document.getElementById('logoEnabled').checked ? 'flex' : 'none';
        }
    }
    
    function handleMultipleImageUpload(files) {
        const validFiles = files.filter(file => 
            file.type.startsWith('image/') && file.size <= 3 * 1024 * 1024
        );
        
        validFiles.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageData = {
                    id: Date.now() + Math.random(),
                    name: file.name,
                    dataUrl: e.target.result,
                    type: 'image'
                };
                
                uploadedImages.push(imageData);
                addImageToGrid(imageData);
            };
            reader.readAsDataURL(file);
        });
        
        if (validFiles.length !== files.length) {
            alert('Some files were skipped. Only image files under 3MB are allowed.');
        }
    }
    
    function addImageToGrid(imageData) {
        const grid = document.getElementById('uploadedImagesGrid');
        const imageDiv = document.createElement('div');
        imageDiv.className = 'uploaded-image-item';
        imageDiv.dataset.imageId = imageData.id;
        
        imageDiv.innerHTML = `
            <img src="${imageData.dataUrl}" alt="${imageData.name}" title="${imageData.name}">
            <button type="button" class="remove-image" onclick="removeUploadedImage('${imageData.id}')">×</button>
        `;
        
        imageDiv.addEventListener('click', function(e) {
            if (!e.target.classList.contains('remove-image')) {
                document.querySelectorAll('.uploaded-image-item').forEach(item => item.classList.remove('selected'));
                this.classList.add('selected');
                addImageIconFromGrid(imageData);
            }
        });
        
        grid.appendChild(imageDiv);
    }
    
    function removeUploadedImage(imageId) {
        uploadedImages = uploadedImages.filter(image => image.id !== imageId);
        document.querySelector(`[data-image-id="${imageId}"]`).remove();
        
        // Also remove any image icons using this image
        document.querySelectorAll(`[data-image-source="${imageId}"]`).forEach(icon => {
            const iconId = icon.id.replace('imageIcon', '');
            removeImageIcon(iconId);
        });
    }
    
    function addImageIconFromGrid(imageData) {
        addImageIcon(imageData);
    }
    
    function addImageIcon(imageData = null) {
        imageIconCount++;
        const imageIconElementsContainer = document.getElementById('imageIconElements');
        
        const elementHtml = `
            <div class="image-icon-control" id="imageIconControl${imageIconCount}">
                <div class="d-flex align-items-center mb-3">
                    ${imageData ? `<div class="image-icon-preview">
                        <img src="${imageData.dataUrl}" alt="${imageData.name}">
                    </div>` : ''}
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Image Icon ${imageIconCount}</h6>
                        <small class="text-muted">${imageData ? imageData.name : 'No image selected'}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                            onclick="removeImageIcon(${imageIconCount})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-4">
                        <div class="form-group mb-2">
                            <label>Size (px)</label>
                            <input type="number" name="image_icons[${imageIconCount}][size]" 
                                   class="form-control" value="50" min="10" max="300"
                                   onchange="updateImageIcon(${imageIconCount})">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group mb-2">
                            <label>X Position</label>
                            <input type="number" name="image_icons[${imageIconCount}][position_x]" 
                                   class="form-control" value="100"
                                   onchange="updateImageIcon(${imageIconCount})">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group mb-2">
                            <label>Y Position</label>
                            <input type="number" name="image_icons[${imageIconCount}][position_y]" 
                                   class="form-control" value="100"
                                   onchange="updateImageIcon(${imageIconCount})">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label>Opacity</label>
                            <input type="range" name="image_icons[${imageIconCount}][opacity]" 
                                   class="form-control" min="0" max="1" step="0.1" value="1"
                                   onchange="updateImageIcon(${imageIconCount})">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label>Rotation (deg)</label>
                            <input type="range" name="image_icons[${imageIconCount}][rotation]" 
                                   class="form-control" min="0" max="360" value="0"
                                   onchange="updateImageIcon(${imageIconCount})">
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="image_icons[${imageIconCount}][image_data]" 
                       value="${imageData ? imageData.dataUrl : ''}" id="imageData${imageIconCount}">
                <input type="hidden" name="image_icons[${imageIconCount}][image_id]" 
                       value="${imageData ? imageData.id : ''}" id="imageId${imageIconCount}">
            </div>
        `;
        
        imageIconElementsContainer.insertAdjacentHTML('beforeend', elementHtml);
        
        // Add visual element to canvas if image data is provided
        if (imageData) {
            const canvasContainer = document.getElementById('canvasContainer');
            const imageIconElement = document.createElement('div');
            imageIconElement.id = `imageIcon${imageIconCount}`;
            imageIconElement.className = 'draggable-element image-icon-element';
            imageIconElement.dataset.imageSource = imageData.id;
            imageIconElement.style.cssText = `
                left: 100px; 
                top: 100px; 
                width: 50px; 
                height: 50px;
            `;
            imageIconElement.innerHTML = `<img src="${imageData.dataUrl}" alt="${imageData.name}">`;
            
            canvasContainer.appendChild(imageIconElement);
            makeImageIconDraggable(imageIconElement, imageIconCount);
        }
    }
    
    function removeImageIcon(iconId) {
        document.getElementById(`imageIconControl${iconId}`).remove();
        const element = document.getElementById(`imageIcon${iconId}`);
        if (element) {
            element.remove();
        }
    }
    
    function updateImageIcon(iconId) {
        const sizeInput = document.querySelector(`input[name="image_icons[${iconId}][size]"]`);
        const xInput = document.querySelector(`input[name="image_icons[${iconId}][position_x]"]`);
        const yInput = document.querySelector(`input[name="image_icons[${iconId}][position_y]"]`);
        const opacityInput = document.querySelector(`input[name="image_icons[${iconId}][opacity]"]`);
        const rotationInput = document.querySelector(`input[name="image_icons[${iconId}][rotation]"]`);
        
        const imageIconElement = document.getElementById(`imageIcon${iconId}`);
        if (imageIconElement) {
            imageIconElement.style.width = sizeInput.value + 'px';
            imageIconElement.style.height = sizeInput.value + 'px';
            imageIconElement.style.left = xInput.value + 'px';
            imageIconElement.style.top = yInput.value + 'px';
            imageIconElement.style.opacity = opacityInput.value;
            imageIconElement.style.transform = `rotate(${rotationInput.value}deg)`;
        }
    }
    
    function makeImageIconDraggable(element, iconId) {
        let isDragging = false;
        let startX, startY;
        
        element.addEventListener('mousedown', function(e) {
            isDragging = true;
            startX = e.clientX - element.offsetLeft;
            startY = e.clientY - element.offsetTop;
            element.classList.add('selected');
            
            // Highlight corresponding control
            document.querySelectorAll('.image-icon-control').forEach(ctrl => ctrl.classList.remove('active'));
            document.getElementById(`imageIconControl${iconId}`).classList.add('active');
        });
        
        document.addEventListener('mousemove', function(e) {
            if (isDragging) {
                const x = e.clientX - startX;
                const y = e.clientY - startY;
                element.style.left = x + 'px';
                element.style.top = y + 'px';
                
                // Update form inputs
                const xInput = document.querySelector(`input[name="image_icons[${iconId}][position_x]"]`);
                const yInput = document.querySelector(`input[name="image_icons[${iconId}][position_y]"]`);
                if (xInput) xInput.value = x;
                if (yInput) yInput.value = y;
            }
        });
        
        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                element.classList.remove('selected');
                document.querySelectorAll('.image-icon-control').forEach(ctrl => ctrl.classList.remove('active'));
            }
        });
    }
    
    function addTextElement(presetText = 'Sample Text') {
        textElementCount++;
        const textElementsContainer = document.getElementById('textElements');
        
        const elementHtml = `
            <div class="text-element-control border rounded p-3 mb-3" id="textControl${textElementCount}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Text Element ${textElementCount}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                            onclick="removeTextElement(${textElementCount})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <div class="form-group mb-2">
                    <label>Text Content</label>
                    <input type="text" name="text_elements[${textElementCount}][text]" 
                           class="form-control" placeholder="Enter text" value="${presetText}"
                           onchange="updateTextElement(${textElementCount})">
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label>Font Size</label>
                            <input type="number" name="text_elements[${textElementCount}][font_size]" 
                                   class="form-control" value="16" min="8" max="72"
                                   onchange="updateTextElement(${textElementCount})">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label>Color</label>
                            <input type="color" name="text_elements[${textElementCount}][color]" 
                                   class="form-control" value="#000000"
                                   onchange="updateTextElement(${textElementCount})">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label>X Position</label>
                            <input type="number" name="text_elements[${textElementCount}][position_x]" 
                                   class="form-control" value="50"
                                   onchange="updateTextElement(${textElementCount})">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-2">
                            <label>Y Position</label>
                            <input type="number" name="text_elements[${textElementCount}][position_y]" 
                                   class="form-control" value="50"
                                   onchange="updateTextElement(${textElementCount})">
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-2">
                    <label>Font Family</label>
                    <select name="text_elements[${textElementCount}][font_family]" class="form-control">
                        <option value="Arial">Arial</option>
                        <option value="Helvetica">Helvetica</option>
                        <option value="Times New Roman">Times New Roman</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Verdana">Verdana</option>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Font Weight</label>
                            <select name="text_elements[${textElementCount}][font_weight]" class="form-control">
                                <option value="normal">Normal</option>
                                <option value="bold">Bold</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Alignment</label>
                            <select name="text_elements[${textElementCount}][alignment]" class="form-control">
                                <option value="left">Left</option>
                                <option value="center">Center</option>
                                <option value="right">Right</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        textElementsContainer.insertAdjacentHTML('beforeend', elementHtml);
        
        // Add visual element to canvas
        const canvasContainer = document.getElementById('canvasContainer');
        const textElement = document.createElement('div');
        textElement.id = `textElement${textElementCount}`;
        textElement.className = 'draggable-element text-element';
        textElement.style.cssText = `
            left: 50px; 
            top: 50px; 
            color: #000000; 
            font-size: 16px; 
            font-family: Arial;
        `;
        textElement.textContent = presetText;
        
        canvasContainer.appendChild(textElement);
        makeDraggable(textElement, textElementCount);
    }
    
    function removeTextElement(elementId) {
        document.getElementById(`textControl${elementId}`).remove();
        const element = document.getElementById(`textElement${elementId}`);
        if (element) {
            element.remove();
        }
    }
    
    function updateTextElement(elementId) {
        const textInput = document.querySelector(`input[name="text_elements[${elementId}][text]"]`);
        const fontSizeInput = document.querySelector(`input[name="text_elements[${elementId}][font_size]"]`);
        const colorInput = document.querySelector(`input[name="text_elements[${elementId}][color]"]`);
        const xInput = document.querySelector(`input[name="text_elements[${elementId}][position_x]"]`);
        const yInput = document.querySelector(`input[name="text_elements[${elementId}][position_y]"]`);
        const fontFamilyInput = document.querySelector(`select[name="text_elements[${elementId}][font_family]"]`);
        const fontWeightInput = document.querySelector(`select[name="text_elements[${elementId}][font_weight]"]`);
        
        const textElement = document.getElementById(`textElement${elementId}`);
        if (textElement) {
            textElement.textContent = textInput.value || 'Sample Text';
            textElement.style.fontSize = fontSizeInput.value + 'px';
            textElement.style.color = colorInput.value;
            textElement.style.left = xInput.value + 'px';
            textElement.style.top = yInput.value + 'px';
            textElement.style.fontFamily = fontFamilyInput.value;
            textElement.style.fontWeight = fontWeightInput.value;
        }
    }
    
    function makeDraggable(element, elementId) {
        let isDragging = false;
        let startX, startY;
        
        element.addEventListener('mousedown', function(e) {
            isDragging = true;
            startX = e.clientX - element.offsetLeft;
            startY = e.clientY - element.offsetTop;
            element.classList.add('selected');
        });
        
        document.addEventListener('mousemove', function(e) {
            if (isDragging) {
                const x = e.clientX - startX;
                const y = e.clientY - startY;
                element.style.left = x + 'px';
                element.style.top = y + 'px';
                
                // Update form inputs
                const xInput = document.querySelector(`input[name="text_elements[${elementId}][position_x]"]`);
                const yInput = document.querySelector(`input[name="text_elements[${elementId}][position_y]"]`);
                if (xInput) xInput.value = x;
                if (yInput) yInput.value = y;
            }
        });
        
        document.addEventListener('mouseup', function() {
            isDragging = false;
            element.classList.remove('selected');
        });
    }
    
    // Make QR and Logo draggable
    makeDraggable(document.getElementById('qrElement'), 'qr');
    makeDraggable(document.getElementById('logoElement'), 'logo');
    
    // Form submission handler
    document.getElementById('templateForm').addEventListener('submit', function(e) {
        // Update positions from visual elements before submission
        const qrElement = document.getElementById('qrElement');
        document.getElementById('qrPosX').value = parseInt(qrElement.style.left);
        document.getElementById('qrPosY').value = parseInt(qrElement.style.top);
        
        if (document.getElementById('logoEnabled').checked) {
            const logoElement = document.getElementById('logoElement');
            document.getElementById('logoPosX').value = parseInt(logoElement.style.left);
            document.getElementById('logoPosY').value = parseInt(logoElement.style.top);
        }
    });
    
    // Load existing template data for edit mode
    @if(isset($template))
    document.addEventListener('DOMContentLoaded', function() {
        loadExistingTemplate();
    });
    
    function loadExistingTemplate() {
        const templateData = @json($template->template_data);
        
        // Load canvas settings
        if (templateData.canvas) {
            updateCanvasSize();
        }
        
        // Load background
        if (templateData.background) {
            if (templateData.background.type === 'color') {
                updateBackgroundColor();
            } else if (templateData.background.type === 'image' && templateData.background.value) {
                const canvas = document.getElementById('canvasContainer');
                canvas.style.backgroundImage = `url(${assetPath}/storage/qr-templates/backgrounds/${templateData.background.value})`;
                canvas.style.backgroundSize = 'cover';
                canvas.style.backgroundPosition = 'center';
            }
        }
        
        // Load QR settings
        if (templateData.qr) {
            updateQRSize();
            updateQRPosition();
        }
        
        // Load logo settings
        if (templateData.logo && templateData.logo.enabled) {
            document.getElementById('logoEnabled').checked = true;
            document.getElementById('logoControls').style.display = 'block';
            document.getElementById('logoElement').style.display = 'block';
            updateLogoSettings();
        }
        
        // Load text elements
        if (templateData.text_elements && templateData.text_elements.length > 0) {
            templateData.text_elements.forEach((textElement, index) => {
                addTextElement(textElement.text);
                // Update the text element with saved data
                setTimeout(() => {
                    const textElementId = textElementCount;
                    document.querySelector(`input[name="text_elements[${textElementId}][text]"]`).value = textElement.text;
                    document.querySelector(`input[name="text_elements[${textElementId}][font_size]"]`).value = textElement.font_size;
                    document.querySelector(`input[name="text_elements[${textElementId}][color]"]`).value = textElement.color;
                    document.querySelector(`input[name="text_elements[${textElementId}][position_x]"]`).value = textElement.position_x;
                    document.querySelector(`input[name="text_elements[${textElementId}][position_y]"]`).value = textElement.position_y;
                    document.querySelector(`select[name="text_elements[${textElementId}][font_family]"]`).value = textElement.font_family || 'Arial';
                    document.querySelector(`select[name="text_elements[${textElementId}][font_weight]"]`).value = textElement.font_weight || 'normal';
                    document.querySelector(`select[name="text_elements[${textElementId}][alignment]"]`).value = textElement.alignment || 'left';
                    updateTextElement(textElementId);
                }, 100);
            });
        }
        
        // Load image icons
        if (templateData.image_icons && templateData.image_icons.length > 0) {
            templateData.image_icons.forEach((imageIcon, index) => {
                if (imageIcon.image_file) {
                    // Create image data object for existing file
                    const imageData = {
                        id: Date.now() + index,
                        name: imageIcon.image_file,
                        dataUrl: `/storage/qr-templates/image-icons/${imageIcon.image_file}`,
                        type: 'image'
                    };
                    
                    addImageIcon(imageData);
                    
                    // Update the image icon with saved data
                    setTimeout(() => {
                        const iconId = imageIconCount;
                        document.querySelector(`input[name="image_icons[${iconId}][size]"]`).value = imageIcon.size || 50;
                        document.querySelector(`input[name="image_icons[${iconId}][position_x]"]`).value = imageIcon.position_x || 100;
                        document.querySelector(`input[name="image_icons[${iconId}][position_y]"]`).value = imageIcon.position_y || 100;
                        document.querySelector(`input[name="image_icons[${iconId}][opacity]"]`).value = imageIcon.opacity || 1;
                        document.querySelector(`input[name="image_icons[${iconId}][rotation]"]`).value = imageIcon.rotation || 0;
                        updateImageIcon(iconId);
                    }, 100);
                }
            });
        }
    }
    @endif
</script>
@endpush
