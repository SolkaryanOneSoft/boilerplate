<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_am',
        'address_en',
        'address_ru',
        'email',
        'phones',
    ];

    protected $table = 'contact_us';

    protected $casts = [
        'phones' => 'array',
    ];

    protected static function booted(): void
    {
        if (request()->isMethod('GET') && request()->header('locale')) {
            static::addGlobalScope('locale', function (Builder $builder) {
                $locale = request()->header('locale');
                $builder->select(
                    'id',
                    "address_{$locale} as address",
                    'email',
                    'phones',
                    'created_at',
                    'updated_at'
                );
            });
        }
    }

    public static function singleton(): self
    {
        return static::first() ?? new static();
    }

}
