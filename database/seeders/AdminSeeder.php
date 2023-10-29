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
        // Admin account
        $email = 'admin@admin.com';
        $password = 'Password1';

        // check if admin account exists
        if (! User::where('email', $email)->first()) {
            // create a new admin user
            $user = new User;
            $user->first_name = 'Admin';
            $user->last_name = 'Admin';
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->save();
        }

        // Guard account
        $email = 'guard1@test.com';
        $password = 'Password1';

        // check if admin account exists
        if (! User::where('email', $email)->first()) {
            // create a new admin user
            $user = new User;
            $user->first_name = 'Guard';
            $user->last_name = 'One';
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->role = 'Guard';
            $user->save();
        }

        // Treasurer account
        $email = 'treasurer@test.com';
        $password = 'Password1';

        // check if admin account exists
        if (! User::where('email', $email)->first()) {
            // create a new admin user
            $user = new User;
            $user->first_name = 'Treasurer';
            $user->last_name = 'Test';
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->role = 'Treasurer';
            $user->save();
        }
    }
}
