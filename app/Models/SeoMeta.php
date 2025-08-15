<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    use HasFactory;

    protected $table = 'seo_meta';
    protected $fillable = [
        'title_am',
        'title_en',
        'title_ru',
        'description_am',
        'description_en',
        'description_ru',
        'keywords',
    ];

    protected $casts = [
        'keywords' => 'array',
    ];

    protected static function booted(): void
    {
        if (request()->isMethod('GET') && request()->header('locale')) {
            static::addGlobalScope('locale', function (Builder $builder) {
                $locale = request()->header('locale');
                $builder->select(
                    'id',
                    "title_{$locale} as title",
                    "description_{$locale} as description",
                    'keywords',
                    'created_at',
                    'updated_at'
                );
            });
        }
    }

}
