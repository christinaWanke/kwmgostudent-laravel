<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function getComments() : JsonResponse {
        $comments = Comment::with(['course', 'user'])->get();
        return response()->json($comments, 200);
    }

    public function getCommentById(string $id) : Comment {
        $comment = Comment::where('id', $id)
            ->with(['course', 'user'])
            ->first();
        return $comment;
    }

    public function save(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $comment = Comment::create($request->all());

            if (isset($request['user']) && is_array($request['user'])){
                foreach ($request['user'] as $u){
                    $user = User::firstOrNew([
                        'firstName' => $u['firstName'],
                        'lastName' => $u['lastName']
                    ]);
                    $comment->user()->save($user);
                }
            }

            DB::commit();
            return response()->json($comment, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving User failed:" . $e->getMessage(), 420);
        }
    }

    public function update(Request $request, string $id) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $comment = Comment::where('id', $id)->first();
            if ($comment != null) {
                $comment->update($request->all());
                $comment->save();
            }
            DB::commit();
            $updatedComment = Comment::where('id', $id)->first();
            // return a vaild http response
            return response()->json($updatedComment, 201);
        }
        catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating User failed: " . $e->getMessage(), 420);
        }
    }

    public function delete(string $id) : JsonResponse
    {
        $comment = Comment::where('id', $id)->first();
        if ($comment != null) {
            $comment->delete();
        }
        else
            throw new \Exception("course couldn't be deleted - it does not exist");
        return response()->json('course (' . $id . ') successfully deleted', 200);

    }
}
