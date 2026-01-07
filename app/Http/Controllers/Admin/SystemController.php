<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SystemController extends Controller
{
    /**
     * Download database backup as SQL file
     * Stores exactly what mysqldump outputs
     *
     * @return \Illuminate\Http\Response
     */
    public function backupDatabase()
    {
        try {
            // Get database configuration
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port', '3306');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');

            // Validate database configuration
            if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
                return back()->with('error', __('Database configuration is incomplete. Please check your .env file.'));
            }

            // Create backup filename with timestamp
            $timestamp = Carbon::now()->format('Y-m-d_His');
            $filename = "backup_{$dbName}_{$timestamp}.sql";
            $backupPath = storage_path("app/backups/{$filename}");

            // Create backups directory if it doesn't exist
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            // Build mysqldump command - output stored exactly as generated
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --port=%s --single-transaction --quick --lock-tables=false --skip-lock-tables --complete-insert --hex-blob --routines --triggers --events --add-drop-table --disable-keys --extended-insert --max_allowed_packet=512M %s > %s 2>&1',
                escapeshellarg($dbUser),
                escapeshellarg($dbPassword),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbName),
                escapeshellarg($backupPath)
            );

            // Execute mysqldump command
            exec($command, $output, $returnVar);

            // Check if file was created
            if (!file_exists($backupPath) || filesize($backupPath) < 100) {
                return back()->with('error', __('Database backup failed. Please check mysqldump is installed and database credentials are correct.'));
            }

            $fileSize = $this->formatBytes(filesize($backupPath));
            Log::info("Database backup completed. File: {$filename}, Size: {$fileSize}");

            // Download the file
            return Response::download($backupPath, $filename, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Database backup error: ' . $e->getMessage());
            return back()->with('error', __('Database backup failed: ') . $e->getMessage());
        }
    }

    /**
     * Display backup management page
     *
     * @return \Illuminate\View\View
     */
    public function backupIndex()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];

        if (file_exists($backupPath)) {
            $files = scandir($backupPath);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $filePath = $backupPath . '/' . $file;
                    $backups[] = [
                        'name' => $file,
                        'size' => $this->formatBytes(filesize($filePath)),
                        'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                        'path' => $filePath
                    ];
                }
            }
        }

        // Sort by date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return view('admin-views.system.backup-index', compact('backups'));
    }

    /**
     * Delete a backup file
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteBackup(Request $request)
    {
        try {
            $filename = $request->input('filename');
            $filePath = storage_path('app/backups/' . $filename);

            if (file_exists($filePath)) {
                unlink($filePath);
                return back()->with('success', __('Backup deleted successfully.'));
            }

            return back()->with('error', __('Backup file not found.'));

        } catch (\Exception $e) {
            Log::error('Delete backup error: ' . $e->getMessage());
            return back()->with('error', __('Failed to delete backup: ') . $e->getMessage());
        }
    }

    /**
     * Download an existing backup file
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function downloadBackup(Request $request)
    {
        try {
            $filename = $request->input('filename');
            $filePath = storage_path('app/backups/' . $filename);

            if (file_exists($filePath)) {
                return Response::download($filePath, $filename, [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                ]);
            }

            return back()->with('error', __('Backup file not found.'));

        } catch (\Exception $e) {
            Log::error('Download backup error: ' . $e->getMessage());
            return back()->with('error', __('Failed to download backup: ') . $e->getMessage());
        }
    }

    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
