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
            $user->contact_email = $email;
            $user->contact_phone = '09090909090';
            $user->save();
        }

        // Guard account
        $email = 'guard1@test.com';

        // check if admin account exists
        if (! User::where('email', $email)->first()) {
            // create a new admin user
            $user = new User;
            $user->first_name = 'Guard';
            $user->last_name = 'One';
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->role = 'Guard';
            $user->contact_email = $email;
            $user->contact_phone = '09123456789';
            $user->save();
        }

        // Treasurer account
        $email = 'treasurer@test.com';

        // check if admin account exists
        if (! User::where('email', $email)->first()) {
            // create a new admin user
            $user = new User;
            $user->first_name = 'Treasurer';
            $user->last_name = 'Test';
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->role = 'Treasurer';
            $user->contact_email = $email;
            $user->contact_phone = '09234567890';
            $user->save();
        }
    }
}
