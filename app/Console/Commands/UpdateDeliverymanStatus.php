<?php

namespace App\Console\Commands;

use App\Models\DeliveryMan;
use App\Models\DeliverymanAttendance;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateDeliverymanStatus extends Command
{
    protected $today ;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:deliveryman-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update deliveryman online/offline status';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $this->today = Carbon::now()->toDateString();
        $deliverymen = DeliveryMan::all();


        foreach ($deliverymen as $deliveryman) {
            $isOnline = $this->checkIfDeliverymanIsOnline($deliveryman);
            dd($isOnline);

            // Update attendance record
            $attendance = DeliverymanAttendance::firstOrNew(['deliveryman_id' => $deliveryman->id]);
            $attendance->is_online = $isOnline;
            $attendance->last_checked = Carbon::now();
            $attendance->save();
        }

        $this->info('Deliveryman statuses have been updated successfully.');
    }

    private function checkIfDeliverymanIsOnline($dm)
    {

        
        $data = [
            'dm_id' => $dm->id,
            'mess_id' => $dm->mess_id ?? null,
            'admin_id' => $dm->admin_id ?? null,
            'restaurant_id' => $dm->admin_id ?? null,
            'active' => $dm->active,
            'last_location' => $request->position
        ];

        // return rand(0, 1) === 1; 
    }
}
