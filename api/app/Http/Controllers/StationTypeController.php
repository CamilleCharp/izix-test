<?php

namespace App\Http\Controllers;

use App\Enums\Current;
use App\Models\StationLevel;
use App\Models\StationType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class StationTypeController extends Controller
{
    public function register(Request $request)
    {
        $level = StationLevel::where('level', $request->input('level'))->first();

        if(!$level) {
            return response()->json(['message' => 'Invalid station level'], Response::HTTP_BAD_REQUEST);
        }

        $validator = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'current' => ['required', Rule::enum(Current::class)],
            'level' => ['bail', 'required', 'integer', 'exists:station_levels,level'],
            'power' => ['required', 'numeric', "min:{$level->minimum_output}", "max:{$level->maximum_output}"],
        ], [
            'name.required' => 'The station type name is required.',
            'name.string' => 'The station type name must be a string.',
            'name.max' => 'The station type name may not be greater than 255 characters.',
            'current.required' => 'The current type is required.',
            'current.enum' => 'The current type must be either AC or DC.',
            'level.required' => 'The station level is required.',
            'level.integer' => 'The station level must be an integer.',
            'level.exists' => 'The selected station level does not exist.',
            'power.required' => 'The power output is required.',
            'power.numeric' => 'The power output must be a number.',
            "power.min" => "The power output must be at least {$level->minimum_output}W.",
            "power.max" => "The power output may not be greater than {$level->maximum_output}W.",
        ]);

        $stationType = new StationType();
        $stationType->name = $request->input('name');
        $stationType->current = $request->input('current');
        $stationType->level()->associate($level);
        $stationType->power = $request->input('power');

        $stationType->save();

        return response(status: Response::HTTP_CREATED);
    }
}
