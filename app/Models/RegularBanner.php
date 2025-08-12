<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegularBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'title_am',
        'title_en',
        'title_ru',
        'description_am',
        'description_en',
        'description_ru',
        'page'
    ];

    protected static function booted(): void
    {
        if (request()->isMethod('GET') && request()->header('locale')) {
            static::addGlobalScope('locale', function (Builder $builder) {
                $locale = request()->header('locale');
                $builder->select(
                    'id',
                    'active',
                    "title_{$locale} as title",
                    "description_{$locale} as description",
                    'page',
                    'created_at',
                    'updated_at'
                );
            });
        }

        static::addGlobalScope(new ActiveScope());
    }

    public function images(): HasMany
    {
        return $this->hasMany(RegularBannerImage::class);
    }

}
