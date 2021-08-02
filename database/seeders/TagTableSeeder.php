<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Tag::count()){
            Tag::truncate();
        }
        $tags = [
            'عمومی',
            'خبری',
            'علم و تکنولوژی',
            'ورزشی',
            'بانوان',
            'آموزشی',
            'طنز',
            'بازی',
            'حوادث',
            'گردشگری',
            'حیوانات',
            'متفرقه',
            'سیاسی',
            'موسیقی',
            'مذهبی',
            'فیلم',
            'تفریحی',
            'سلامت',
            'کارتون',
            'هنری',
        ];
        foreach ($tags as $tag){
            Tag::create(['title'=>$tag]);
        }
    }
}
