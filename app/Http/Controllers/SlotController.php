<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlotController extends Controller
{



    public function getBookedSlotsOfTutor(int $id) : JsonResponse {
        $slots = Slot::whereHas('course', function ($query) use ($id) {
            $query->where('user_id', '=', $id);
        })->whereHas('slot', function ($query) {
            $query->where('booked', '=', true);
        })->with(['course', 'slot.user'])->orderBy('date', 'DESC')->orderBy('from', 'DESC')->get();
        return response()->json($slots, 200);
    }


    /*public function getBookedSlotsOfStudent(int $id) : JsonResponse {
        $timeslots = Timeslot::whereHas('course', function ($query) use ($id) {
            $query->where('user_id', '=', $id);
        })->whereHas('timeslotAgreement', function ($query) {
            $query->where('accepted', '=', true);
        })->with(['service.user', 'timeslotAgreement'])->orderBy('date', 'DESC')->orderBy('from', 'DESC')->get();
        return response()->json($timeslots, 200);
    }*/



    public function getSlotById(string $id) : Slot {
        $slot = Slot::where('id', $id)
            ->with(['users'])
            ->first();
        return $slot;
    }


    public function getTimeslots(string $id) : Slot {
        $timeslot = Slot::where('id', $id)
            ->with(['users'])
            ->first();
        return $timeslot;
    }


    public function save(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $timeslot = Slot::create($request->all());
            DB::commit();
            return response()->json($timeslot, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving Timeslot failed:" . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if User updated successfully, throws excpetion if not
     */
    public function update(Request $request, string $id) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $timeslot = Slot::where('id', $id)->first();
            if ($timeslot != null) {
                $timeslot->update($request->all());
                $timeslot->save();
            }
            DB::commit();
            $timeslot1 = Slot::where('id', $id)->first();
            // return a valid http response
            return response()->json($timeslot1, 201);
        }
        catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating Timeslot failed: " . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if Service was deleted successfully, throws excpetion if not
     */
    public function delete(string $id) : JsonResponse
    {
        $timeslot = Slot::where('id', $id)->first();
        if ($timeslot != null) {
            $timeslot->delete();
        }
        else
            throw new \Exception("Timeslot couldn't be deleted - it does not exist");
        return response()->json('Timeslot (' . $id . ') successfully deleted', 200);
    }
}
