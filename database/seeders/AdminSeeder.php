<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $email = 'admin@admin.com';
        $password = 'Password1';

        // check if admin account exists
        if (! User::where('email', $email)->first()) {
            // create a new admin user
            $user = new User;
            $user->name = 'Admin';
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->save();
        }
    }
}
