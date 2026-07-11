<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_AWAITING_VIDEO = 'awaiting_video';
    const STATUS_VIDEO_UPLOADED = 'video_uploaded';
    const STATUS_AWAITING_PAYMENT_QR = 'awaiting_payment_qr';
    const STATUS_RECEIPT_UPLOADED = 'receipt_uploaded';
    const STATUS_PAID = 'paid';

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'rider_id',
        'rider_price',
        'total_price',
        'status',
        'video_path',
        'video_proof_path',
        'payment_method',
        'custom_name',
        'product_name',
        'product_type',
        'quantity',
        'address',
        'is_pasabuy_request',
        'buyer_last_read_at',
        'seller_last_read_at',
        'paid_at',
    ];

    protected $casts = [
        'buyer_last_read_at' => 'datetime',
        'seller_last_read_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'order_id');
    }

    public function getFormattedStatusAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }
    
}