<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QRTemplate;

class CleanupQRTemplateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr-templates:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up QR template data to remove large image data and keep only filenames';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting QR template data cleanup...');
        
        $templates = QRTemplate::all();
        $cleanedCount = 0;
        
        foreach ($templates as $template) {
            $templateData = $template->template_data;
            $hasChanges = false;
            
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
                    
                    // Check if we removed image_data
                    if (isset($icon['image_data'])) {
                        $hasChanges = true;
                    }
                }
                
                if ($hasChanges) {
                    $templateData['image_icons'] = $cleanedIcons;
                    $template->template_data = $templateData;
                    $template->save();
                    $cleanedCount++;
                    
                    $this->line("Cleaned template: {$template->name}");
                }
            }
        }
        
        $this->info("Cleanup completed! Cleaned {$cleanedCount} templates.");
        $this->info("Memory usage should now be significantly reduced.");
        
        return 0;
    }
}
