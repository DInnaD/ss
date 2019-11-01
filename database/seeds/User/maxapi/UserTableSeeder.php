<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $admin = [
            'first_name' => 'Inna',
            'last_name' => 'Dan',
            'role' => 'admin',
            'email' => 'admin@localhost',
            'password' => Hash::make('123456'),
        ];
        User::create($admin);
         $noadmin = [
                'first_name' => 'Inna',
                'last_name' => 'Dan',
                'role' => 'user',
                'email' => 'noadmin@localhost',
                'password' => Hash::make('123456'),
            ];
        User::create($noadmin);
        factory(User::class, 48)->create();
    }
}
