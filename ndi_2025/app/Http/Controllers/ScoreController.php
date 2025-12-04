<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 

class ScoreController extends Controller
{
    public function store(Request $request)
    {
        error_log($request->type);

        $request->validate([
            'type' => 'required|string',
            'ref' => 'required|string',
            'nbpoint' => 'required|integer',
        ]);

        $userId = Auth::id();

        // Vérifier si score existe déjà
        $score = Score::where('user_id', $userId)
            ->where('ref', $request->ref)
            ->first();

        if ($score) {
            // Update
            $score->update([
                'nbpoint' => $request->nbpoint,
                'type' => $request->type,
            ]);
        } else {
            // Create
            $score = Score::create([
                'user_id' => $userId,
                'ref' => $request->ref,
                'type' => $request->type,
                'nbpoint' => $request->nbpoint,
            ]);
        }

        return response()->json(['success' => true, 'score' => $score]);
    }
}
