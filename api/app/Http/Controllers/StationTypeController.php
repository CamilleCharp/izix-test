<?php

namespace App\Http\Controllers;

use App\Enums\Current;
use App\Http\Requests\StationTypeRegisterRequest;
use App\Models\StationLevel;
use App\Models\StationType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class StationTypeController extends Controller
{
    public function register(StationTypeRegisterRequest $request)
    {
        $level = StationLevel::where('level', $request->input('level'))->first();

        if(!$level) {
            return response()->json(['message' => 'Invalid station level'], Response::HTTP_BAD_REQUEST);
        }

        if($request->input('power') < $level->minimum_output || $request->input('power') > $level->maximum_output) {
            return response()->json([
                'message' => "Power output must be between {$level->minimum_output}W and {$level->maximum_output}W for level {$level->level}."
            ], Response::HTTP_BAD_REQUEST);
        }

        $stationType = new StationType();
        $stationType->name = $request->input('name');
        $stationType->current = $request->input('current');
        $stationType->level()->associate($level);
        $stationType->power = $request->input('power');

        $stationType->save();

        return response(status: Response::HTTP_CREATED);
    }
}
