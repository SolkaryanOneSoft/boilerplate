<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegularBannerImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'regular_banner_id',
        'path',
        'file_type'
    ];

    public function regularBanner(): BelongsTo
    {
        return $this->belongsTo(RegularBanner::class);
    }

}
