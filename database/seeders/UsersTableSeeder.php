<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->CreateAdminUser();
        for ($i=1 ; $i<5 ; $i++){
            $this->CreateUser($i);
        }
    }

    private function CreateAdminUser()
    {
        if (User::count()){
            Channel::truncate();
            User::truncate();
        }
        User::factory()->make([
            'name'   => 'admin',
            'mobile' => '+989112223344',
            'email'  => 'admin@aparat.com',
            'type'   => User::TYPES_ADMIN//'admin'
        ])->save();
        $this->command->info('admin were created successfully');
    }

    private function CreateUser($num)
    {
        User::factory()->make([
            'name'   => 'کاربر ' . $num,
            'mobile' => '+989'. str_repeat($num,9),
            'email'  => 'user'. $num .'@aparat.com',
        ])->save();
//        User::factory()->create();
        $this->command->info('user '.$num.' were created successfully');
    }

}

