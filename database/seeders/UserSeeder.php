<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $newUser = new User;
        $newUser->name="Israel David Villarroel Moreno";
        $newUser->email="israeldavidvm@gmail.com";
        $newUser->password=bcrypt("Password1234.");
        $newUser->save();

    }
}
