<?php

namespace Database\Seeders;

use App\Models\Slot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->persnum = "S19104560tu";
        $user->firstName = "Test";
        $user->lastName = "User";
        $user->email = "test@gmail.com";
        $user->password = bcrypt('secret');
        $user->description = "I bims eins test user!";
        $user->semester = 6;

        $user->save();

        $slots = Slot::all()->pluck('id');
        $user->slots()->sync($slots);

        $user->save();

        $user2 = new User;
        $user2->persnum = "S1910456024";
        $user2->firstName = "Änni";
        $user2->lastName = "Reischl";
        $user2->email = "änni@gmail.com";
        $user2->password = bcrypt('secret');
        $user2->description = "I bims eins Änni!";
        $user2->semester = 6;
        $user2->isTutor = true;

        $user2->save();

        $user2->slots()->sync($slots);

        $user2->save();
    }
}
