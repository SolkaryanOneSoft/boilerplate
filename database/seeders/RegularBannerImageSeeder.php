<?php

namespace Database\Seeders;

use App\Models\RegularBannerImage;
use Illuminate\Database\Seeder;

class RegularBannerImageSeeder extends Seeder
{
    public function run(): void
    {
        RegularBannerImage::create([
            'regular_banner_id' => 1,
            'path' => '/storage/images/AusmGVg7NnEfrVp3kMKh8Q5r0guwiDqmjCHusHD3.png',
            'file_type' => 'image'
        ]);
        RegularBannerImage::create([
            'regular_banner_id' => 2,
            'path' => '/storage/images/AusmGVg7NnEfrVp3kMKh8Q5r0guwiDqmjCHusHD3.png',
            'file_type' => 'image'
        ]);
        RegularBannerImage::create([
            'regular_banner_id' => 3,
            'path' => '/storage/images/AusmGVg7NnEfrVp3kMKh8Q5r0guwiDqmjCHusHD3.png',
            'file_type' => 'image'
        ]);
        RegularBannerImage::create([
            'regular_banner_id' => 1,
            'path' => '/storage/images/AusmGVg7NnEfrVp3kMKh8Q5r0guwiDqmjCHusHD3.png',
            'file_type' => 'image'
        ]);
        RegularBannerImage::create([
            'regular_banner_id' => 4,
            'path' => '/storage/images/AusmGVg7NnEfrVp3kMKh8Q5r0guwiDqmjCHusHD3.png',
            'file_type' => 'image'
        ]);
        RegularBannerImage::create([
            'regular_banner_id' => 3,
            'path' => '/storage/images/AusmGVg7NnEfrVp3kMKh8Q5r0guwiDqmjCHusHD3.png',
            'file_type' => 'image'
        ]);
    }
}
