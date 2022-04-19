<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Ramsey\Uuid\Type\Time;

class SlotsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $slot = new Slot;
        $slot-> day = Carbon::createFromFormat("Y.m.d", "2021.04.10", "Europe/Vienna");
        $slot-> from = Carbon::createFromTime(13, 00, 00, "Europe/Vienna");
        $slot-> to = Carbon::createFromTime(17, 00, 00, "Europe/Vienna");

        $course = Course::all()->first();
        $slot->course()->associate($course);

        $slot->save();

        $user = User::all()->pluck('id');
        $slot->users()->sync($user);
        $slot->save();





    }
}
