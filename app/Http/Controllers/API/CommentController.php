<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Exception;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function fetch(Request $request) {
        $comments = Comment::with('guest')->get();

        return ResponseFormatter::success(
            $comments,
            'Comments found'
        );
    }

    public function create(Request $request) {
        try {
            $request->validate([
                'guest_id' => ['required'],
                'comment' => ['required', 'string']
            ]);

            $comment = Comment::create([
                'guest_id' => $request->guest_id,
                'comment' => $request->comment
            ]);

            if(!$comment) {
                throw new Exception('Comment not created');
            }

            return ResponseFormatter::success($comment, 'Comment added');
        } catch(Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function show(Request $request, $id) {
        $comment = Comment::find($id);

        if (!$comment) {
            throw new Exception('Comment not found');
        }

        return ResponseFormatter::success($comment, 'Guest Found !');
    }

    public function update(Request $request, $id) {

        try {
            $request->validate([
                'guest_id' => ['required', 'integer'],
                'comment' => ['required', 'string']
            ]);

            $comment = Comment::find($id);

            $comment->update([
                'guest_id' => $request->guest_id,
                'comment' => $request->comment
            ]);

            if($comment) {
                throw new Exception('Comment not updated');
            }

            return ResponseFormatter::success($comment, 'Comment updated');
        } catch(Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $comment = Comment::find($id);

            if (!$comment) {
                throw new Exception('Comment not found');
            }

            $comment->delete();

            return ResponseFormatter::success(null,'Comment deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
