<?php

namespace App\Events\User\Restaurant;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ClearOrderSessions
{
    use Dispatchable, SerializesModels;

    public $customer_id;
    public $clear_if_clear_within_passed;

    /**
     * Create a new event instance.
     */
    public function __construct(int $customer_id,bool $clear_if_clear_within_passed = false)
    {
        $this->customer_id = $customer_id;
        $this->clear_if_clear_within_passed = $clear_if_clear_within_passed;

        $this->handle($this);
    }


    /**
     * Handle the event.
     */
    public function handle(self $event): void
    {

        $shouldCheckTime = $event->clear_if_clear_within_passed ;

        $latestSession = DB::table('order_sessions')
            ->where('customer_id', $event->customer_id)
            ->when($shouldCheckTime, function ($query) {
                $query->where('updated_at', '<=', now()->subMinutes(15));
            })
            ->orderByDesc('updated_at')
            ->select('id')
            ->first();
        // dd($latestSession);

        if ($latestSession) {
            DB::table('order_sessions')
                ->where('id', $latestSession->id)
                ->where('is_locked', false)
                ->update([
                    'applied_coupons' => null,
                    'dm_tips' => 0,
                    'loved_one_data' => null,
                    'cooking_instruction' => null,
                    'delivery_instruction' => null,
                ]);
        }
    }
}
