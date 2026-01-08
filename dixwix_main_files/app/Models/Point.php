<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'through_user_id',
        'package_id',
        'system_fee',
        'type',
        'points',
        'amount',
        'total_coins',
        'description',
    ];
  protected $casts = [
    'approved_at' => 'timestamp',
];

    const TRANS_TYPE_REWARD = 1;
    const TRANS_TYPE_PURCHASED = 2;
    const TRANS_TYPE_AUTO = 3;
    const TRANS_TYPE_GIFT = 4;

    public static $TRANS_TYPE_TEXT = [
        self::TRANS_TYPE_REWARD   => "Reward",
        self::TRANS_TYPE_PURCHASED  => "Purchase",
        self::TRANS_TYPE_AUTO  => "Auto Transfer",
        self::TRANS_TYPE_GIFT  => "Gifted",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
  
    // User referenced by through_user_id
    public function throughUser()
    {
        return $this->belongsTo(User::class, 'through_user_id');
    }

    public function package()
    {
        return $this->belongsTo(CoinPackage::class, 'package_id');
    }

}
