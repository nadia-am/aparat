<?php

namespace Database\Seeders;

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
        $this->CreateUser();
    }

    private function CreateAdminUser()
    {
        User::factory()->make([
            'name' => 'admin',
            'mobile'=>'+989112223344',
            'email' =>'admin@aparat.com',
            'type'=>'admin'
        ])->save();
        $this->command->info('admin were created successfully');
    }

    private function CreateUser()
    {
        User::factory()->create();
        $this->command->info('user were created successfully');
    }

}

