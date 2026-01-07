<?php

namespace App\Services;

class JsonDataServicecopy
{
    protected $filePath;
    protected $today;
    public $dm_id = null;
    public $name = null;
    public $mess_id = null;
    public $admin_id = null;
    public $restaurant_id = null;
    public $active = null;
    public $currentOrders = null;
    public $last_location = null;
    public $updated_at = null;

    public function __construct($dmId)
    {
        $this->today = date('Y-m-d'); // Define today’s date
        $this->dm_id = $dmId;
        $directory = storage_path('app/json');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        $filename = "$this->dm_id-{$this->today}.json";
        $this->filePath = storage_path("app/json/$filename");

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }

        $this->cleanupOldFiles($directory);
        $this->readData();  // Automatically read existing data on object creation
    }

    protected function cleanupOldFiles($directory)
    {
        $today = date('Y-m-d');

        $files = glob($directory . '/*.json');
        foreach ($files as $file) {
            $fileName = basename($file, '.json');
            $fileDate = substr($fileName, strpos($fileName, '-') + 1);

            if ($fileDate < $today) {
                unlink($file);
            }
        }
    }

    public function readData()
    {
        $fileContents = file_get_contents($this->filePath);
        $data = json_decode($fileContents, true);

        // Merge the existing data into the class properties
        $this->name = $data['name'] ?? $this->name;
        $this->dm_id = $data['dm_id'] ?? $this->dm_id;
        $this->mess_id = $data['mess_id'] ?? $this->mess_id;
        $this->admin_id = $data['admin_id'] ?? $this->admin_id;
        $this->restaurant_id = $data['restaurant_id'] ?? $this->restaurant_id;
        $this->active = $data['active'] ?? $this->active;
        $this->currentOrders = $data['currentOrders'] ?? $this->currentOrders;
        $this->last_location = $data['last_location'] ?? $this->last_location;
        $this->updated_at = $data['updated_at'] ?? $this->updated_at;

        return $this;
    }

    public function save()
    {
        // Read existing data
        $existingData = json_decode(file_get_contents($this->filePath), true);

        // Merge existing data with current data (class properties)
        $updatedData = array_merge($existingData ?? [], [
            'name' => $this->name,
            'dm_id' => $this->dm_id,
            'mess_id' => $this->mess_id,
            'admin_id' => $this->admin_id,
            'restaurant_id' => $this->restaurant_id,
            'active' => $this->active,
            'currentOrders' => $this->currentOrders,
            'last_location' => $this->last_location,
            'updated_at' => $this->updated_at,
        ]);

        // Write the merged data back to the file
        file_put_contents($this->filePath, json_encode($updatedData, JSON_PRETTY_PRINT));
    }
}
