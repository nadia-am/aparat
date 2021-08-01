<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Category::count()){
            Category::truncate();
        }

        //TODO add banner and icon
        $categories = [
            'عمومی'=>['icon'=>'', 'banner'=>'' ],
            'خبری'=>['icon'=>'', 'banner'=>'' ],
            'علم و تکنولوژی'=>['icon'=>'', 'banner'=>'' ],
            'ورزشی'=>['icon'=>'', 'banner'=>'' ],
            'بانوان'=>['icon'=>'', 'banner'=>'' ],
            'آموزشی'=>['icon'=>'', 'banner'=>'' ],
            'طنز'=>['icon'=>'', 'banner'=>'' ],
            'بازی'=>['icon'=>'', 'banner'=>'' ],
            'حوادث'=>['icon'=>'', 'banner'=>'' ],
            'گردشگری'=>['icon'=>'', 'banner'=>'' ],
            'حیوانات'=>['icon'=>'', 'banner'=>'' ],
            'متفرقه'=>['icon'=>'', 'banner'=>'' ],
            'سیاسی'=>['icon'=>'', 'banner'=>'' ],
            'موسیقی'=>['icon'=>'', 'banner'=>'' ],
            'مذهبی'=>['icon'=>'', 'banner'=>'' ],
            'فیلم'=>['icon'=>'', 'banner'=>'' ],
            'تفریحی'=>['icon'=>'', 'banner'=>'' ],
            'سلامت'=>['icon'=>'', 'banner'=>'' ],
            'کارتون'=>['icon'=>'', 'banner'=>'' ],
            'هنری'=>['icon'=>'', 'banner'=>'' ],
        ];
        foreach ($categories as $category=>$options){
            Category::create([
                'title'=> $category,
                'icon'=>$options['icon'] ,
                'banner'=>$options['banner'] ,
           ] );
        }
    }
}
