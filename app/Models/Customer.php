<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;
use PDO;

class Customer extends Model implements AuthenticatableContract
{
    use Authenticatable,Notifiable, HasApiTokens;

    protected $fillable = ['id','f_name','l_name','phone','email','role_id','is_phone_verified','email_verified_at','image','address','dob','merital_status','anniversary','gender','status',
    'password','email_verification_token','fcm_token','otp','otp_expiry','remember_token','referral_code','referred_by','successful_orders','loyalty_points'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dietCoupons()
    {
        return $this->belongsToMany(DietCoupon::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function subscription()
    {
        return $this->hasMany(CustomerSubscriptionTransactions::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function subscriptionOrders()
    {
        return $this->hasMany(SubscriptionOrderDetails::class);
    }

    public function createdBy()
    {
        return $this->hasMany(CustomerAddedBy::class);
    }
    public function customerAddress()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    public function loyaltyPointTransactions()
    {
        return $this->hasMany(LoyaltyPointTransaction::class);
    }


    public function scopeCustomerCreatedBy($query, $addedBY)
    {
        return $query->whereHas('createdBy', function ($q) use ($addedBY) {
            $q->where('added_by', $addedBY);
        })->latest()->get();
    }

    public function scopeActiveSubscription($query)
    {
        $today = Carbon::today()->toDateString();
        return $query->with(['subscription' => function ($q) use ($today) {
            $q->with('package')->where('expiry', '>', $today);
        }])->latest()->get();
    }

    public function favoriteRestaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'favorites', 'customer_id', 'restaurant_id');
    }

    public function favoriteFoods()
    {
        return $this->belongsToMany(Food::class, 'favorites', 'customer_id', 'food_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }



    public function scopeIsActive($query, $status= true)
    {
        return $query->where('status',$status);
    }


    public function routeNotificationForFcm()
    {
        // Log::info('FCM Token: ' . $this->fcm_token); // Log the token
        return $this->fcm_token;
    }

    // Chat relationships
    public function sentMessages()
    {
        return $this->morphMany(Message::class, 'sender');
    }

    public function sentConversations()
    {
        return $this->morphMany(Conversation::class, 'sender');
    }

    public function receivedConversations()
    {
        return $this->morphMany(Conversation::class, 'receiver');
    }

    public function conversations()
    {
        return $this->sentConversations()->union($this->receivedConversations());
    }

    public function getFullNameAttribute()
    {
        return $this->f_name . ' ' . $this->l_name;
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    // Referral System Relationships
    public function sponsoredReferrals()
    {
        return $this->hasMany(Referral::class, 'sponsor_id');
    }

    public function usedReferrals()
    {
        return $this->belongsToMany(Referral::class, 'referral_uses', 'beneficiary_id', 'referral_id')
            ->withPivot('used_at')
            ->withTimestamps();
    }

    public function referralUses()
    {
        return $this->hasMany(ReferralUse::class, 'beneficiary_id');
    }

    public function referrer()
    {
        return $this->belongsTo(Customer::class, 'referred_by');
    }

    public function referredUsers()
    {
        return $this->hasMany(Customer::class, 'referred_by');
    }

    public function referralRewards()
    {
        return $this->hasMany(ReferralUserReward::class, 'user_id');
    }

    public function sponsorRewards()
    {
        return $this->hasMany(ReferralUserReward::class, 'sponsor_id');
    }

    // Generate unique referral code for user
    public function generateReferralCode()
    {
        if (!$this->referral_code) {
            $code = Referral::generateUniqueCode();
            
            // Create the referral record
            $referral = Referral::create([
                'referral_code' => $code,
                'sponsor_id' => $this->id,
                'total_uses' => 0,
                'is_active' => true
            ]);
            
            $this->referral_code = $code;
            $this->save();
        }
        return $this->referral_code;
    }

    // Get available user referral rewards
    public function getAvailableUserRewards()
    {
        return $this->referralRewards()->userAvailable()->get();
    }

    // Get available sponsor referral rewards  
    public function getAvailableSponsorRewards()
    {
        return $this->sponsorRewards()->sponsorAvailable()->get();
    }

    // Get unlocked but unclaimed user rewards
    public function getUnclaimedUserRewards()
    {
        return $this->referralRewards()->unlocked()->userUnclaimed()->get();
    }

    // Get unlocked but unclaimed sponsor rewards
    public function getUnclaimedSponsorRewards()
    {
        return $this->sponsorRewards()->unlocked()->sponsorUnclaimed()->get();
    }

    // Update order count and check for reward unlocks
    public function updateOrderCount()
    {
        $this->increment('successful_orders');
        
        // Check if any rewards should be unlocked (for this user as beneficiary)
        $rewards = $this->referralRewards()->where('is_unlocked', false)->get();
        
        foreach ($rewards as $reward) {
            $reward->user_current_orders = $this->successful_orders;
            $reward->save();
            $reward->checkAndUnlock();
        }
    }
}
