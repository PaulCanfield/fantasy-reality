<?php

namespace App\Http\Controllers;

use App\Episode;
use App\Http\Requests\MakePredictionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PredictionsController extends Controller
{
    public function store(Episode $episode, MakePredictionRequest $request) {
        $episode->addPrediction($request->validated());
        return redirect($episode->season->path());
    }

    public function complete(Episode $episode) {
        if (!Gate::allows('complete', $episode)) {
            return response('Unauthorized', 403);
        }

        $episode->completePredictions();

        return redirect($episode->season->path());
    }
}