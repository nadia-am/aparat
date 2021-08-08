<?php

namespace Database\Seeders;

use App\Models\Playlist;
use Illuminate\Database\Seeder;

class PlaylistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Playlist::count()){
            Playlist::truncate();
        }

        $list = [
            'لیست پخش یک',
            'لیست پخش دو',
        ];
        foreach ($list as $each){
            Playlist::create([
                'title'=> $each,
                'user_id'=> 1,
            ]);
        }
    }
}
