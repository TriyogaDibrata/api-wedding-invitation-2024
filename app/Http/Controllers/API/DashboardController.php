<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $data = [];
        $total_companions = 0;
        $total_guest = Guest::all()->count();
        $attended_guest = Guest::where('is_attend', 1)->get()->count();
        $unattended_guest = Guest::where('is_attend', 0)->get()->count();
        $unconfirmed_guest = Guest::where('is_attend', null)->get()->count();

        $companions = Guest::select('companions')->where('companions', '!=', null)->get();

        for($i = 0; $i < count($companions); $i++) {
            $new = explode(',', $companions[$i]->companions);
            $total_companions += count($new);
        }
        

        $data['total_guest'] = $total_guest;
        $data['attended_guest'] = $attended_guest;
        $data['unattended_guest'] = $unattended_guest;
        $data['unconfirmed_guest'] = $unconfirmed_guest;
        $data['total_companions'] = $total_companions;

        return ResponseFormatter::success($data, "Fetched");
    }
}
