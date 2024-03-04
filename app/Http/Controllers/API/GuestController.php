<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Guest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function fetch(Request $request) {
        $guests = Guest::all();

        return ResponseFormatter::success(
            $guests,
            'Guests found'
        );
    }

    public function create(Request $request) {
        $random = Str::random(5);

        $count_companions = 0;

        try {
            $request->validate([
                'name' => ['required', 'string']
            ]);

            if($request->companions) {
                $count_companions = count(explode(",", $request->companions));
            }

            $guest = Guest::create([
                'name' => $request->name,
                'guest_slug' => Str::lower(Str::replace(' ', '-', $request->name)).'-'.$random,
                'companions' => $request->companions ? $request->companions : null,
                'total_companions' => $count_companions,
                'is_attend' => $request->is_attend,
            ]);

            if(!$guest) {
                throw new Exception('Guest not created');
            }

            return ResponseFormatter::success($guest, 'Guest created');
        } catch(Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        } 
    }

    public function show(Request $request, $id) {
        $guest = Guest::find($id);

        if (!$guest) {
            throw new Exception('Guest not found');
        }

        return ResponseFormatter::success($guest, 'Guest Found !');
    }

    public function update(Request $request, $id) {
        $random = Str::random(5);
        $count_companions = 0;

        try {
            $request->validate([
                'name' => ['required', 'string']
            ]);

            if($request->companions) {
                $count_companions = count(explode(",", $request->companions));
            }

            $guest = Guest::find($id);

            if (!$guest) {
                throw new Exception('Guest not found');
            }

            $guest->update([
                'name' => $request->name,
                'guest_slug' => Str::lower(Str::replace(' ', '-', $request->name)).'-'.$random,
                'companions' => $request->companions ? $request->companions : null,
                'total_companions' => $count_companions,
                'is_attend' => $request->is_attend,
            ]);

            return ResponseFormatter::success($guest, 'Guest updated');
        } catch(Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        } 
    }

    public function destroy($id)
    {
        try {
            $guest = Guest::find($id);


            if (!$guest) {
                throw new Exception('Guest not found');
            }

            $guest->delete();

            return ResponseFormatter::success(null,'Guest deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function rsvp(Request $request, $id) {
        $count_companions = 0;

        try {
            $request->validate([
                'name' => ['required', 'string'],
                'is_attend' => ['required']
            ]);

            if($request->companions) {
                $count_companions = count(explode(",", $request->companions));
            }

            $guest = Guest::find($id);

            if (!$guest) {
                throw new Exception('Guest not found');
            }

            $update = $guest->update([
                'name' => $request->name,
                'companions' => $request->companions ? $request->companions : null,
                'total_companions' => $count_companions,
                'is_attend' => $request->is_attend,
            ]);

            if($update) {
                if($request->comment) {
                    $comment = Comment::where('guest_id', $id)->first();
                    if($comment) {
                        $comment->update([
                            'guest_id' => $id,
                            'comment' => $request->comment
                        ]);
                        return ResponseFormatter::success('', "Answer Added");
                    } else {
                        $comment = Comment::create([
                            'guest_id' => $id,
                            'comment' => $request->comment
                        ]);
                        return ResponseFormatter::success('', "Answer Added");
                    }
                }
                return ResponseFormatter::success('', "Answer Added");
            } else {
                return ResponseFormatter::error("Cannot add data");
            }
        } catch(Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        } 
    }
}
