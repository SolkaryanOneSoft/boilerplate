<?php

namespace Database\Seeders;

use App\Models\ContactUs;
use Illuminate\Database\Seeder;

class ContactUsSeeder extends Seeder
{
    public function run(): void
    {
        ContactUs::create([
            'address_am' => 'Ազատության 24/16',
            'address_en' => 'Azatutyan 24/16',
            'address_ru' => 'Азатутян 24/16',
            'email' => 'suport@gmail.com',
            'phones' => ['+37499000000', '+37477000000', '+37433000000'],
        ]);
    }
}
