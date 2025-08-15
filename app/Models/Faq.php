<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'sort_number',
        'question_am',
        'question_en',
        'question_ru',
        'answer_am',
        'answer_en',
        'answer_ru'
    ];

    protected static function booted(): void
    {
        if (request()->isMethod('GET') && request()->header('locale')) {
            static::addGlobalScope('locale', function (Builder $builder) {
                $locale = request()->header('locale');
                $builder->select(
                    'id',
                    'active',
                    'sort_number',
                    "question_{$locale} as question",
                    "answer_{$locale} as answer",
                    'created_at',
                    'updated_at'
                );
            });
        }

        static::addGlobalScope(new ActiveScope());
    }

}
