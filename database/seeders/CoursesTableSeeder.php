<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Image;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Time;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $course = new Course;
        $course->title = "WHM";
        $course->description = "Letzter Teil der Trilogie";
        $course->semester = 2;
        $course->professor = "Augstein";

        $u = User::all()->first();
        $course->user()->associate($u);

        $course->save();

        // add images to book
        $image = new Image;
        $image->title = "Cover 1";
        $image->url = "https://images-na.ssl-images-amazon.com/images/I/61h%2BnIJyVFL._SX333_BO1,204,203,200_.jpg";

        $image2 = new Image;
        $image2->title = "Cover 2";
        $image2->url = "https://images-eu.ssl-images-amazon.com/images/I/516KV5tjulL._AC_US327_FMwebp_QL65_.jpg";
        $course->images()->saveMany([$image,$image2]);


        $course->save();

        $slot = Slot::all()->pluck('id');
        $course->slot()->saveMany($slot);

        $course->save();


        $course2 = new Course;
        $course2->title = "Bachelorarbeit";
        $course2->description = "Its the final countdown";
        $course2->semester = 6;
        $course2->professor = "Betreuer";

        //$u = User::all()->first();
        $course2->user()->associate($u);

        $course2->save();

        //$slot = Slot::all()->pluck('id');
        $course2->slot()->saveMany($slot);

        $course2->save();


    }
}
