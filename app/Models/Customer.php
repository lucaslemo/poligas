<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'code',
        'phone_number',
    ];

    /**
     * Log all attributes on the model
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function codeFormatted(): string
    {
        if (!$this->code) {
            return '-';
        }
        return formatCnpjCpf($this->code);
    }

    public function phoneNumberFormatted(): string
    {
        if (!$this->phone_number) {
            return '-';
        }
        return formatPhoneNumber($this->phone_number);
    }

    /**
     * Get the addresses for the customer.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'get_customer_id');
    }
}
