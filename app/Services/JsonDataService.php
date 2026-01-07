<?php

namespace App\Services;

use App\Models\DmCurrentLocation;

class JsonDataService
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
        // $this->readData();  // Automatically read existing data on object creation
    }



    public function readData()
    {
        $result = DmCurrentLocation::where('dm_id', $this->dm_id)->first();
        
        if ($result) {
            $this->name = $result->name;
            $this->dm_id = $result->dm_id;
            $this->mess_id = $result->mess_id;
            $this->admin_id = $result->admin_id;
            $this->restaurant_id = $result->restaurant_id;
            $this->active = $result->active;
            $this->currentOrders = $result->current_orders;
            $this->last_location = json_decode($result->last_location,true);
            $this->updated_at = $result->updated_at;
        }
        return $this;
    }

     public function save(): bool
    {


        // Merge existing data with current data (class properties)
        if (!is_null($this->name) && $this->name !== '') {
            $updatedData['name'] = $this->name;
        }
        if (!is_null($this->dm_id) && $this->dm_id !== '') {
            $updatedData['dm_id'] = $this->dm_id;
        }
        if (!is_null($this->mess_id) && $this->mess_id !== '') {
            $updatedData['mess_id'] = $this->mess_id;
        }
        if (!is_null($this->admin_id) && $this->admin_id !== '') {
            $updatedData['admin_id'] = $this->admin_id;
        }
        if (!is_null($this->restaurant_id) && $this->restaurant_id !== '') {
            $updatedData['restaurant_id'] = $this->restaurant_id;
        }
        if (!is_null($this->active) && $this->active !== '') {
            $updatedData['active'] = $this->active;
        }
        if (!is_null($this->currentOrders) && $this->currentOrders !== '') {
            $updatedData['current_orders'] = $this->currentOrders;
        }
        if (!is_null($this->last_location) && $this->last_location !== '') {
            $updatedData['last_location'] = json_encode($this->last_location);
        }
        if (!is_null($this->updated_at) && $this->updated_at !== '') {
            $updatedData['updated_at'] = $this->updated_at;
        }

        // $updatedData = [
        //     'name' => $this->name,
        //     'dm_id' => $this->dm_id,
        //     'mess_id' => $this->mess_id,
        //     'admin_id' => $this->admin_id,
        //     'restaurant_id' => $this->restaurant_id,
        //     'active' => $this->active,
        //     'currentOrders' => $this->currentOrders,
        //     'last_location' => json_encode($this->last_location),
        //     'updated_at' => $this->updated_at,
        // ];

        $result = DmCurrentLocation::updateOrCreate(['dm_id' => $this->dm_id], $updatedData);
        if ($result) {
            return true;
        }
        return false;
    }
}
