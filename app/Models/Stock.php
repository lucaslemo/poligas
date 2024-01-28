<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vendor_value',
        'status',
        'get_product_id',
        'get_brand_id',
        'get_vendor_id',
        'get_sale_id',
    ];

    public function statusFormatted(): string
    {
        switch ($this->status) {
            case 'available':
                return 'Disponível';
            case 'unavailable':
                return 'Indisponível';
            case 'sold':
                return 'Vendido';
            default:
                return '-';
        }
    }

    /**
     * Log all attributes on the model
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    /**
     * Get the brand that owns the stock.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'get_brand_id');
    }

    /**
     * Get the product type that owns the stock.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'get_product_id');
    }

    /**
     * Get the vendor that owns the stock.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'get_vendor_id');
    }

    /**
     * Get the sales for the stock.
     */
    public function sales()
    {
        return $this->belongsToMany(
            Sale::class,
            'sale_has_stocks',
            'get_stock_id',
            'get_sale_id',
        )->withPivot(['sale_value']);
    }
}
