<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Image;
use App\Models\Slot;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index() : JsonResponse{
        $courses = Course::with(['slots', 'images', 'user', 'comments'])->get();
        return response()->json($courses, 200);
    }

    public function findByTitle(string $title) : Course {
        return Course::where('title', $title)
            ->with(['slots', 'images', 'user', 'comments'])
            ->first();

    }

    public function findByCnum(string $cnum) : Course {
        return Course::where('cnum', $cnum)
            ->with(['slots', 'images', 'user', 'comments.user'])
            ->first();
    }

    public function checkCNUM(string $cnum){
        $course = Course::where('cnum', $cnum)->first();
        return $course != null ?
            response()->json(true, 200) :
            response()->json(false, 200);

    }

    public function findBySemester(int $semester) : Course {
        return Course::where('semester', $semester)
            ->with(['slots', 'images', 'user'])
            ->first();
    }

    public function findBySearchTerm(string $searchTerm) {
        return Course::with(['slots', 'images', 'user'])
            ->where('title', 'LIKE', '%' . $searchTerm. '%')
            ->orWhere('description' , 'LIKE', '%' . $searchTerm. '%')

            /* search term in users name */
            ->orWhereHas('user', function($query) use ($searchTerm) {
                $query->where('firstName', 'LIKE', '%' . $searchTerm. '%')
                    ->orWhere('lastName', 'LIKE',  '%' . $searchTerm. '%');
            })->get();
    }

    //parse date from http request into php parameter
    private function parseRequest(Request $request) : Request {
        if (isset($request['date'])) {
            $date = new DateTime($request->date);
            $request['date'] = $date;
        }

        if (isset($request['from'])) {
            $request['from'] = Carbon::createFromFormat('H:i:s', $request->from, "Europe/Vienna");
        }

        if (isset($request['to'])) {
            $request['to'] = Carbon::createFromFormat('H:i:s', $request->to, "Europe/Vienna");
        }

        return $request;
    }

    public function save(Request $request) : JsonResponse{

        $request = $this->parseRequest($request);

        DB::beginTransaction();
        try {
            $course = Course::create($request->all());


            if(isset($request['slots']) && is_array($request['slots'])){
                foreach ($request['slots'] as $s){
                    $slots = Slot::firstOrNew([
                        'day' => $s['day'],
                        'from' => $s['from'],
                        'to' => $s['to'],
                        'isBooked' => $s['isBooked']

                    ]);
                    //$course->slots()->saveMany(Slot::all()->pluck('id'));
                    $slots->course()->associate(Course::all()->first());
                    $course->slots()->save($slots);
                    $slots->users()->sync(User::all()->pluck('id'));
                    $course->slots()->save($slots);

                }
            }

            if(isset($request['images']) && is_array($request['images'])){
                foreach ($request['images'] as $img){
                    $image = Image::firstOrNew([
                        'url' => $img['url'],
                        'title' => $img['title']
                    ]);
                    //$course->images()->saveMany();
                    $course->images()->save($image);
                }
            }

            if(isset($request['user']) && is_array($request['user'])){
                foreach ($request['user'] as $u){
                    $user = User::firstOrNew([
                        'firstName' => $u['firstName'],
                        'lastName' => $u['lastName']
                    ]);
                    //$course->user()->associate(User::all()->first());
                    $course->user()->save($user);
                    $user->slots()->sync(Slot::all()->pluck('id'));
                    $course->user()->save($user);
                }
            }


            DB::commit();
            return response()->json($course, 201);
        }
        catch (\Exception $e){
            DB::rollBack();
            return response()->json("Saving course failed!" . $e->getMessage(), 420);
        }
    }

    public function update(Request $request, string $cnum) : JsonResponse
    {

        DB::beginTransaction();
        try {
            $course = Course::with(['slots', 'images', 'user'])
                ->where('cnum', $cnum)->first();

            if ($course != null) {
                $request = $this->parseRequest($request);
                $course->update($request->all());

                //delete all old slots
                $course->slots()->delete();
                // save slots
                if (isset($request['slots']) && is_array($request['slots'])) {
                    foreach ($request['slots'] as $s) {
                        $slots = Slot::firstOrNew([
                            'day' => $s['day'],
                            'from' => $s['from'],
                            'to' => $s['to'],
                            'isBooked' => $s['isBooked']
                        ]);
                        $course->slots()->save($slots);
                    }
                }

                //delete all old images
                $course->images()->delete();
                // save images
                if(isset($request['images']) && is_array($request['images'])){
                    foreach ($request['images'] as $img){
                        $image = Image::firstOrNew([
                            'url' => $img['url'],
                            'title' => $img['title']
                        ]);
                        $course->images()->save($image);
                    }
                }
            }
            DB::commit();
            $c = Course::with(['slots', 'images', 'user'])
                ->where('cnum', $cnum)->first();
            // return a vaild http response
            return response()->json($c, 201);
        }
        catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating course failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(string $cnum) : JsonResponse
    {
        $course = Course::where('cnum', $cnum)->first();
        if ($course != null) {
            $course->delete();
        }
        else
            throw new \Exception("course couldn't be deleted - it does not exist");
        return response()->json('course (' . $cnum . ') successfully deleted', 200);

    }

}

/*-----------------------------------------------------------------------------------------------------------------------------------------

-----------------------------------------------------------------------------------------------------------------------------------------*/
