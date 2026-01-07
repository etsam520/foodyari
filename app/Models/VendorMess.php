<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class VendorMess extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','logo','cover_photo','phone','radius','address','tax','vendor_id',
        'cash_on_delivery','status','coordinates','latitude','longitude','zone_id','badges','status','phone','mess_no'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }
    
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function subscription ()
    {
        return $this->hasMany(Subscription::class, 'mess_id');
    }

    public function timing()
    {
        return $this->hasMany(MessTiming::class, 'mess_id');
    }

    public function scopeFindNearbyLocations()
    {
        // Input latitude and longitude
        $userLocation = Session::get('userLocation');
        // dd($userLocation);
        if(!$userLocation){
            return null;
        }
        

        $lat =$userLocation->lat;
        $lng = $userLocation->lng;

        
        $radius = 10; 

        // Haversine formula to calculate distances
        $rows = self::select('*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) *
                cos( radians( latitude ) )
                * cos( radians( longitude ) - radians(?)
                ) + sin( radians(?) ) *
                sin( radians( latitude ) ) )
                ) AS distance', [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc')
            ->get();

        return $rows;
    }

    public function scopeIsActive($query, $active=true){
        return $query->where('status',$active);
    }
}
