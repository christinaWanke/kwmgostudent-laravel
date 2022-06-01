<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * returns a status of 200 and all Users if successful
     */
    public function getUsers() : JsonResponse {
        $users = User::with(['course', 'slots'])->get();
        return response()->json($users, 200);
    }
    /**
     * finds and returns a User based on their id
     */
    public function getUserById(string $id) : User {
        $user = User::where('id', $id)
            ->with(['course', 'slots'])
            ->first();
        return $user;
    }
    /**
     * returns 200 if User could be created successfully, throws excpetion if not
     */
    public function save(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $User = User::create($request->all());
            DB::commit();
            return response()->json($User, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving User failed:" . $e->getMessage(), 420);
        }
    }

    /**
     * returns 200 if User updated successfully, throws excpetion if not
     */
    public function update(Request $request, string $id) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $User = User::where('id', $id)->first();
            if ($User != null) {
                $User->update($request->all());
                $User->save();
            }
            DB::commit();
            $User1 = User::where('id', $id)->first();
            // return a vaild http response
            return response()->json($User1, 201);
        }
        catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating User failed: " . $e->getMessage(), 420);
        }
    }

    /*public function isTutor(string $persnum): JsonResponse{
        $user = User::where('persnum', $persnum)->first();
        return response()->json(boolval($user->isTutor));
    }*/

    /*public function isTutor(string $persnum): JsonResponse{
        $user = User::where('persnum', $persnum)->first();
        return $user != null ?
            response()->json(true, 200) :
            response()->json(false, 200);
    }*/

    public function isTutor(string $id): JsonResponse{
        $user = User::where('id', $id)->first();
        return response()->json(boolval($user->isTutor));
    }


}
