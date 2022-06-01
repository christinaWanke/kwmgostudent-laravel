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
        $user->persnum = "S19104560ll";
        $user->firstName = "Linda";
        $user->lastName = "Lehrer";
        $user->email = "linda@gmail.com";
        $user->password = bcrypt('secret');
        $user->description = "Ich bin Linda, Studiere KWM im 6. Semester und habe mich auf E-Learning spezialisiert.";
        $user->semester = 6;
        $user->isTutor = true;
        $user->save();

        $slots = Slot::all()->pluck('id');
        $user->slots()->sync($slots);

        $user->save();

        $usert = new User;
        $usert->persnum = "S19104560tt";
        $usert->firstName = "Tom";
        $usert->lastName = "Tutor";
        $usert->email = "tom@gmail.com";
        $usert->password = bcrypt('secret');
        $usert->description = "Ich bin Tom, studiere KWM und meine Leidenschaft ist das Programmieren. Ich bin der Mann, wenn ihr Hilfe in dem Bereich braucht.";
        $usert->semester = 4;
        $usert->isTutor = true;
        $usert->save();
        $usert->slots()->sync($slots);


        $user2 = new User;
        $user2->persnum = "S19104560hh";
        $user2->firstName = "Herbert";
        $user2->lastName = "Herbst";
        $user2->email = "herbert@gmail.com";
        $user2->password = bcrypt('secret');
        $user2->description = "Ich bin Herbert und bin Student in KWM. Ich freue mich auf Nachhilfe!";
        $user2->semester = 3;

        $user2->save();

        $user2->slots()->sync($slots);

        $user2->save();


        $user3 = new User;
        $user3->persnum = "S19104560ee";
        $user3->firstName = "Emmi";
        $user3->lastName = "Erichs";
        $user3->email = "emmi@gmail.com";
        $user3->password = bcrypt('secret');
        $user3->description = "Ich bin Emmi und bin Studentin in KWM. Ich freue mich auf Nachhilfe!";
        $user3->semester = 1;

        $user3->save();

        $user3->slots()->sync($slots);

        $user3->save();
    }
}
