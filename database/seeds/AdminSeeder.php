<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
           'name'   =>  'ronaldo',
           'email'  =>  'caominhduc1999@gmail.com',
           'password'   =>  bcrypt('123456789'),
            'role'  =>  'administrator'
        ]);
    }
}
