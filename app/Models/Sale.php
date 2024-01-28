<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total_value',
        'payment_date',
        'get_customer_id',
        'get_user_id',
        'get_deliveryman_user_id',
        'get_payment_type_id',
    ];

    /**
     * Log all attributes on the model
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    /**
     * Get the customer that owns the sale.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'get_customer_id');
    }

    /**
     * Get the user that owns the sale.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'get_user_id');
    }

    /**
     * Get the deliveryman that owns the sale.
     */
    public function deliveryman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'get_deliveryman_user_id');
    }

    /**
     * Get the payment type that owns the sale.
     */
    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'get_payment_type_id');
    }

    /**
     * Get the stocks for the sale.
     */
    public function stocks()
    {
        return $this->belongsToMany(
            Stock::class,
            'sale_has_stocks',
            'get_sale_id',
            'get_stock_id',
        )->withPivot(['sale_value']);
    }
}
