<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    public $fillable = [
        'user_id',
        'price',
        'address',
        'status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
    ];

    /**
     * @return BelongsTo
     */
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id', 'name']);
    }
}
