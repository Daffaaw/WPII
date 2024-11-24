<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i=0; $i < 496; $i++) {
            User::create([
                'name' => 'User Ke-'.$i,
                'email' => 'USer'.$i.'@gmail.com',
                'password' => Hash::make('password'),
            ]);
        }
        
    }
}
