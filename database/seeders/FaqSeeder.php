<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::create([
            'active' => true,
            'sort_number' => 5,
            'question_am' => 'Lorem Ipsum նչպիսին է Aldus PageMaker-ը, որը ներառում է Lorem Ipsum-ի տարատեսակներ:',
            'question_en' => 'Lorem Ipsum is simply n',
            'question_ru' => 'Lorem ах которых используется Lorem Ipsum.',
            'answer_am' => 'Lorem Ipsum-ը տպագրության  ւթյան համար նախատեսված մոդելային տեքստ է: ինչպիսին է Aldus PageMaker-ը, որը ներառում է Lorem Ipsum-ի տարատեսակներ:',
            'answer_en' => 'Lorem Ipsum is simply nd typesetting industry. Lorem Ipsum has  ',
            'answer_ru' => 'Lorem Ipsum - это текст Lorem Ipsum явля Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.'
        ]);
        Faq::create([
            'active' => true,
            'sort_number' => 5,
            'question_am' => 'Lorem Ipsum նչպիսին է Aldus PageMaker-ը, որը ներառում է Lorem Ipsum-ի տարատեսակներ:',
            'question_en' => 'Lorem Ipsum is simply n',
            'question_ru' => 'Lorem ах которых используется Lorem Ipsum.',
            'answer_am' => 'Lorem Ipsum-ը տպագրության  ւթյան համար նախատեսված մոդելային տեքստ է: ինչպիսին է Aldus PageMaker-ը, որը ներառում է Lorem Ipsum-ի տարատեսակներ:',
            'answer_en' => 'Lorem Ipsum is simply nd typesetting industry. Lorem Ipsum has  ',
            'answer_ru' => 'Lorem Ipsum - это текст Lorem Ipsum явля Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.'
        ]);
        Faq::create([
            'active' => true,
            'sort_number' => 3,
            'question_am' => 'Lorem Ipsum նչպիսին է Aldus PageMaker-ը, որը ներառում է Lorem Ipsum-ի տարատեսակներ:',
            'question_en' => 'Lorem Ipsum is simply n',
            'question_ru' => 'Lorem ах которых используется Lorem Ipsum.',
            'answer_am' => 'Lorem Ipsum-ը տպագրության  ւթյան համար նախատեսված մոդելային տեքստ է: ինչպիսին է Aldus PageMaker-ը, որը ներառում է Lorem Ipsum-ի տարատեսակներ:',
            'answer_en' => 'Lorem Ipsum is simply nd typesetting industry. Lorem Ipsum has  ',
            'answer_ru' => 'Lorem Ipsum - это текст Lorem Ipsum явля Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.'
        ]);
        Faq::create([
            'active' => true,
            'sort_number' => 4,
            'question_am' => 'Lorem Ipsum նչպիսին է Aldus PageMaker-ը, որը ներառում է Lorem Ipsum-ի տարատեսակներ:',
            'question_en' => 'Lorem Ipsum is simply n',
            'question_ru' => 'Lorem ах которых используется Lorem Ipsum.',
            'answer_am' => 'Lorem Ipsum-ը տպագրության  ւթյան համար նախատեսված մոդելային տեքստ է: ինչպիսին է Aldus PageMaker-ը, որը ներառում է Lorem Ipsum-ի տարատեսակներ:',
            'answer_en' => 'Lorem Ipsum is simply nd typesetting industry. Lorem Ipsum has  ',
            'answer_ru' => 'Lorem Ipsum - это текст Lorem Ipsum явля Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.'
        ]);
        Faq::create([
            'active' => true,
            'sort_number' => 1,
            'question_am' => 'Lorem Ipsum նչպիսին է Aldus PageMaker-ը, որը ներառում է Lorem Ipsum-ի տարատեսակներ:',
            'question_en' => 'Lorem Ipsum is simply n',
            'question_ru' => 'Lorem ах которых используется Lorem Ipsum.',
            'answer_am' => 'Lorem Ipsum-ը տպագրության  ւթյան համար նախատեսված մոդելային տեքստ է: ինչպիսին է Aldus PageMaker-ը, որը ներառում է Lorem Ipsum-ի տարատեսակներ:',
            'answer_en' => 'Lorem Ipsum is simply nd typesetting industry. Lorem Ipsum has  ',
            'answer_ru' => 'Lorem Ipsum - это текст Lorem Ipsum явля Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.'
        ]);
    }
}
