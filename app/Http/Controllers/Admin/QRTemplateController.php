<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QRTemplate;
use App\Models\Zone;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QRTemplateController extends Controller
{
    public function index()
    {
        $templates = QRTemplate::with(['zone', 'creator'])
            ->select(['id', 'name', 'zone_id', 'background_type', 'background_value', 'is_default', 'status', 'created_by', 'created_at', 'updated_at'])
            ->latest()
            ->paginate(20);
        
        return view('admin-views.qr-template.index', compact('templates'));
    }

    public function create()
    {
        $zones = Zone::where('status', 1)->get();
        return view('admin-views.qr-template.create', compact('zones'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'zone_id' => 'required|exists:zones,id',
            'background_type' => 'required|in:color,image',
            'background_color' => 'required_if:background_type,color|string',
            'background_image' => 'required_if:background_type,image|image|mimes:jpeg,png,jpg,gif|max:5120',
            'template_width' => 'required|numeric|min:200|max:2000',
            'template_height' => 'required|numeric|min:200|max:2000',
            'qr_size' => 'required|numeric|min:50|max:500',
            'qr_position_x' => 'required|numeric',
            'qr_position_y' => 'required|numeric',
            'logo_enabled' => 'boolean',
            'logo_size' => 'nullable|numeric|min:10|max:200',
            'logo_position_x' => 'nullable|numeric',
            'logo_position_y' => 'nullable|numeric',
            'custom_icons.*' => 'nullable|image|mimes:png|max:2048',
            'image_icons.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'text_elements' => 'nullable|array',
            'image_icons' => 'nullable|array',
            'text_elements.*.text' => 'required_with:text_elements|string',
            'text_elements.*.font_size' => 'required_with:text_elements|numeric|min:8|max:72',
            'text_elements.*.color' => 'required_with:text_elements|string',
            'text_elements.*.position_x' => 'required_with:text_elements|numeric',
            'text_elements.*.position_y' => 'required_with:text_elements|numeric',
            'text_elements.*.font_family' => 'nullable|string',
            'text_elements.*.font_weight' => 'nullable|string',
            'text_elements.*.alignment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $backgroundValue = null;
            
            if ($request->background_type === 'image' && $request->hasFile('background_image')) {
                $backgroundValue = $this->uploadBackgroundImage($request->file('background_image'));
            } elseif ($request->background_type === 'color') {
                $backgroundValue = $request->background_color;
            }

            // Handle custom icon uploads
            $customIcons = [];
            if ($request->hasFile('custom_icons')) {
                $customIcons = $this->uploadCustomIcons($request->file('custom_icons'));
            }
            
            // Handle multiple image icon uploads
            $imageIcons = [];
            if ($request->hasFile('image_icons')) {
                $imageIcons = $this->uploadImageIcons($request->file('image_icons'));
            }

            $templateData = [
                'canvas' => [
                    'width' => (int) $request->template_width,
                    'height' => (int) $request->template_height,
                ],
                'background' => [
                    'type' => $request->background_type,
                    'value' => $backgroundValue,
                ],
                'qr' => [
                    'size' => (int) $request->qr_size,
                    'position_x' => (int) $request->qr_position_x,
                    'position_y' => (int) $request->qr_position_y,
                    'error_correction' => $request->qr_error_correction ?? 'M',
                ],
                'logo' => [
                    'enabled' => (bool) $request->logo_enabled,
                    'source' => $request->logo_source ?? 'upload',
                    'preset_icon' => $request->preset_icon,
                    'custom_icons' => $customIcons,
                    'selected_icon' => $request->selected_icon,
                    'size' => $request->logo_enabled ? (int) $request->logo_size : null,
                    'position_x' => $request->logo_enabled ? (int) $request->logo_position_x : null,
                    'position_y' => $request->logo_enabled ? (int) $request->logo_position_y : null,
                ],
                'text_elements' => $this->processTextElements($request->text_elements ?? []),
                'image_icons' => $this->processImageIconsFromRequest($request),
            ];

            QRTemplate::create([
                'name' => $request->name,
                'zone_id' => $request->zone_id,
                'template_data' => $templateData,
                'background_type' => $request->background_type,
                'background_value' => $backgroundValue,
                'status' => true,
                'is_default' => (bool) $request->is_default,
                'created_by' => Auth::guard('admin')->id(),
            ]);

            return redirect()->route('admin.qr-template.index')
                ->with('success', 'QR Template created successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create template: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $template = QRTemplate::with('zone')->findOrFail($id);
        $zones = Zone::where('status', 1)->get();
        
        return view('admin-views.qr-template.create', compact('template', 'zones'));
    }

    public function update(Request $request, $id)
    {
        $template = QRTemplate::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'zone_id' => 'required|exists:zones,id',
            'background_type' => 'required|in:color,image',
            'background_color' => 'required_if:background_type,color|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'template_width' => 'required|numeric|min:200|max:2000',
            'template_height' => 'required|numeric|min:200|max:2000',
            'qr_size' => 'required|numeric|min:50|max:500',
            'qr_position_x' => 'required|numeric',
            'qr_position_y' => 'required|numeric',
            'logo_enabled' => 'boolean',
            'logo_size' => 'nullable|numeric|min:10|max:200',
            'logo_position_x' => 'nullable|numeric',
            'logo_position_y' => 'nullable|numeric',
            'custom_icons.*' => 'nullable|image|mimes:png|max:2048',
            'image_icons.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'text_elements' => 'nullable|array',
            'image_icons' => 'nullable|array',
            'text_elements.*.text' => 'required_with:text_elements|string',
            'text_elements.*.font_size' => 'required_with:text_elements|numeric|min:8|max:72',
            'text_elements.*.color' => 'required_with:text_elements|string',
            'text_elements.*.position_x' => 'required_with:text_elements|numeric',
            'text_elements.*.position_y' => 'required_with:text_elements|numeric',
            'text_elements.*.font_family' => 'nullable|string',
            'text_elements.*.font_weight' => 'nullable|string',
            'text_elements.*.alignment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $backgroundValue = $template->background_value;
            
            if ($request->background_type === 'image' && $request->hasFile('background_image')) {
                // Delete old image if exists
                if ($template->background_type === 'image' && $template->background_value) {
                    Storage::disk('public')->delete('qr-templates/backgrounds/' . $template->background_value);
                }
                $backgroundValue = $this->uploadBackgroundImage($request->file('background_image'));
            } elseif ($request->background_type === 'color') {
                // Delete old image if switching from image to color
                if ($template->background_type === 'image' && $template->background_value) {
                    Storage::disk('public')->delete('qr-templates/backgrounds/' . $template->background_value);
                }
                $backgroundValue = $request->background_color;
            }

            // Handle custom icon uploads
            $customIcons = [];
            if ($request->hasFile('custom_icons')) {
                $customIcons = $this->uploadCustomIcons($request->file('custom_icons'));
            }

            $templateData = [
                'canvas' => [
                    'width' => (int) $request->template_width,
                    'height' => (int) $request->template_height,
                ],
                'background' => [
                    'type' => $request->background_type,
                    'value' => $backgroundValue,
                ],
                'qr' => [
                    'size' => (int) $request->qr_size,
                    'position_x' => (int) $request->qr_position_x,
                    'position_y' => (int) $request->qr_position_y,
                    'error_correction' => $request->qr_error_correction ?? 'M',
                ],
                'logo' => [
                    'enabled' => (bool) $request->logo_enabled,
                    'source' => $request->logo_source ?? 'upload',
                    'preset_icon' => $request->preset_icon,
                    'custom_icons' => $customIcons,
                    'selected_icon' => $request->selected_icon,
                    'size' => $request->logo_enabled ? (int) $request->logo_size : null,
                    'position_x' => $request->logo_enabled ? (int) $request->logo_position_x : null,
                    'position_y' => $request->logo_enabled ? (int) $request->logo_position_y : null,
                ],
                'text_elements' => $this->processTextElements($request->text_elements ?? []),
                'image_icons' => $this->processImageIconsFromRequest($request),
            ];

            $template->update([
                'name' => $request->name,
                'zone_id' => $request->zone_id,
                'template_data' => $templateData,
                'background_type' => $request->background_type,
                'background_value' => $backgroundValue,
                'is_default' => (bool) $request->is_default,
            ]);

            return redirect()->route('admin.qr-template.index')
                ->with('success', 'QR Template updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update template: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $template = QRTemplate::findOrFail($id);
            
            // Delete background image if exists
            if ($template->background_type === 'image' && $template->background_value) {
                Storage::disk('public')->delete('qr-templates/backgrounds/' . $template->background_value);
            }
            
            $template->delete();
            
            return response()->json(['success' => true, 'message' => 'Template deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete template']);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $template = QRTemplate::findOrFail($id);
            $template->update(['status' => !$template->status]);
            
            $message = $template->status ? 'Template activated successfully' : 'Template deactivated successfully';
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status']);
        }
    }

    public function setDefault($id)
    {
        try {
            $template = QRTemplate::findOrFail($id);
            
            // Remove default from other templates in the same zone
            QRTemplate::where('zone_id', $template->zone_id)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
            
            // Set this template as default
            $template->update(['is_default' => true]);
            
            return response()->json(['success' => true, 'message' => 'Template set as default successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to set as default']);
        }
    }

    public function preview($id)
    {
        $template = QRTemplate::with('zone')->findOrFail($id);
        return view('admin-views.qr-template.preview', compact('template'));
    }

    public function getZoneTemplates(Request $request)
    {
        $zoneId = $request->query('zone_id');
        
        if (!$zoneId) {
            return response()->json(['templates' => []]);
        }

        $templates = QRTemplate::where('zone_id', $zoneId)
            ->where('status', true)
            ->select('id', 'name', 'is_default')
            ->get();

        return response()->json(['templates' => $templates]);
    }

    private function uploadBackgroundImage($file)
    {
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('qr-templates/backgrounds', $filename, 'public');
        return $filename;
    }

    private function uploadCustomIcons($files)
    {
        $uploadedIcons = [];
        
        foreach ($files as $file) {
            if ($file->isValid() && $file->getClientOriginalExtension() === 'png') {
                $filename = Str::random(40) . '.png';
                $file->storeAs('qr-templates/icons', $filename, 'public');
                
                $uploadedIcons[] = [
                    'id' => Str::random(10),
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'url' => Storage::url('qr-templates/icons/' . $filename),
                ];
            }
        }
        
        return $uploadedIcons;
    }

    private function processTextElements($textElements)
    {
        $processed = [];
        
        foreach ($textElements as $element) {
            if (!empty($element['text'])) {
                $processed[] = [
                    'text' => $element['text'],
                    'font_size' => (int) ($element['font_size'] ?? 16),
                    'color' => $element['color'] ?? '#000000',
                    'position_x' => (int) ($element['position_x'] ?? 50),
                    'position_y' => (int) ($element['position_y'] ?? 50),
                    'font_family' => $element['font_family'] ?? 'Arial',
                    'font_weight' => $element['font_weight'] ?? 'normal',
                    'alignment' => $element['alignment'] ?? 'left',
                ];
            }
        }
        
        return $processed;
    }

    private function processIconElements($iconElements)
    {
        $processed = [];
        
        foreach ($iconElements as $element) {
            if (!empty($element['icon'])) {
                $processed[] = [
                    'icon' => $element['icon'],
                    'size' => (int) $element['size'],
                    'position_x' => (int) $element['position_x'],
                    'position_y' => (int) $element['position_y'],
                    'color' => $element['color'] ?? '#000000',
                ];
            }
        }
        
        return $processed;
    }
    
    private function uploadImageIcons($files)
    {
        $uploadedFiles = [];
        
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('qr-templates/image-icons', $filename, 'public');
                $uploadedFiles[] = $filename;
            }
        }
        
        return $uploadedFiles;
    }
    

    
    private function processImageIcons($imageIconsData, $uploadedFilenames = [])
    {
        $processed = [];
        $fileIndex = 0;
        
        if (!is_array($imageIconsData)) {
            return $processed;
        }
        
        foreach ($imageIconsData as $index => $icon) {
            // Skip if no image data and no uploaded file
            if (empty($icon['image_data']) && (!isset($uploadedFilenames[$fileIndex]) || empty($uploadedFilenames[$fileIndex]))) {
                continue;
            }
            
            $processed[] = [
                'image_file' => $uploadedFilenames[$fileIndex] ?? null,
                'image_data' => $icon['image_data'] ?? null,
                'size' => (int) ($icon['size'] ?? 50),
                'position_x' => (int) ($icon['position_x'] ?? 100),
                'position_y' => (int) ($icon['position_y'] ?? 100),
                'opacity' => (float) ($icon['opacity'] ?? 1.0),
                'rotation' => (int) ($icon['rotation'] ?? 0),
            ];
            
            // Only increment file index if we used an uploaded file
            if (isset($uploadedFilenames[$fileIndex]) && !empty($uploadedFilenames[$fileIndex])) {
                $fileIndex++;
            }
        }
        
        return $processed;
    }
    
    private function processImageIconsFromRequest($request)
    {
        $processed = [];
        $uploadedFiles = [];
        
        // Handle file uploads first
        if ($request->hasFile('image_icons')) {
            $uploadedFiles = $this->uploadImageIcons($request->file('image_icons'));
        }
        
        // Get form data for image icons (existing files)
        $imageIconsData = $request->input('image_icons', []);
        
        if (!is_array($imageIconsData)) {
            return $processed;
        }
        
        $fileIndex = 0;
        
        foreach ($imageIconsData as $index => $icon) {
            // Check if this icon has a filename or uploaded file
            $hasExistingFile = !empty($icon['filename']);
            $hasUploadedFile = isset($uploadedFiles[$fileIndex]);
            
            if (!$hasExistingFile && !$hasUploadedFile) {
                continue;
            }
            
            $processed[] = [
                'filename' => $hasUploadedFile ? $uploadedFiles[$fileIndex] : ($icon['filename'] ?? null),
                'size' => (int) ($icon['size'] ?? 50),
                'position_x' => (int) ($icon['position_x'] ?? 100),
                'position_y' => (int) ($icon['position_y'] ?? 100),
                'opacity' => (float) ($icon['opacity'] ?? 1.0),
                'rotation' => (int) ($icon['rotation'] ?? 0),
            ];
            
            // Only increment file index if we used an uploaded file
            if ($hasUploadedFile) {
                $fileIndex++;
            }
        }
        
        return $processed;
    }

    public function cleanupTemplateData()
    {
        $templates = QRTemplate::all();
        
        foreach ($templates as $template) {
            $templateData = $template->template_data;
            
            if (isset($templateData['image_icons'])) {
                $cleanedIcons = [];
                
                foreach ($templateData['image_icons'] as $icon) {
                    // Remove image_data and only keep filename and position data
                    $cleanedIcon = [
                        'filename' => $icon['filename'] ?? $icon['image_file'] ?? null,
                        'size' => $icon['size'] ?? 50,
                        'position_x' => $icon['position_x'] ?? 100,
                        'position_y' => $icon['position_y'] ?? 100,
                        'opacity' => $icon['opacity'] ?? 1.0,
                        'rotation' => $icon['rotation'] ?? 0,
                    ];
                    
                    if ($cleanedIcon['filename']) {
                        $cleanedIcons[] = $cleanedIcon;
                    }
                }
                
                $templateData['image_icons'] = $cleanedIcons;
                $template->template_data = $templateData;
                $template->save();
            }
        }
        
        return response()->json(['message' => 'Template data cleaned successfully']);
    }
}
