<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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



    //     public function load(Request $request)
    // {
    // $sort = $request->get('sort', 'total');
    // $direction = $request->get('direction', 'desc');
    // $search = $request->get('search', '');

    // $query = Score::query()
    //     ->selectRaw("
    //         scores.user_id,
    //         users.name,
    //         scores.type,
    //         SUM(scores.nbpoint) AS total_points
    //     ")
    //     ->join('users', 'scores.user_id', '=', 'users.id')
    //     ->groupBy('scores.user_id', 'users.name', 'scores.type');

    // if ($search !== '') {
    //     $query->where('users.name', 'like', "%{$search}%");
    // }

    // if ($sort === 'user') {
    //     $query->orderBy('users.name', $direction);
    // } elseif ($sort === 'type') {
    //     $query->orderBy('scores.type', $direction);
    // } else {
    //     $query->orderBy('total_points', $direction);
    // }

    // return response()->json($query->get());
    // }


        public function load(Request $request)
    {
        $limit = (int) ($request->limit ?? 10);
        $offset = (int) ($request->offset ?? 0);

        $sort = $request->get('sort', 'total');
        $direction = $request->get('direction', 'desc');
        $search = $request->get('search', '');

        $query = Score::query()
            ->selectRaw("
                scores.user_id,
                users.name,
                scores.type,
                SUM(scores.nbpoint) AS total_points
            ")
            ->join('users', 'scores.user_id', '=', 'users.id')
            ->groupBy('scores.user_id', 'users.name', 'scores.type');

        if ($search !== '') {
            $query->where('users.name', 'like', "%{$search}%");
        }

        if ($sort === 'user') {
            $query->orderBy('users.name', $direction);
        } elseif ($sort === 'type') {
            $query->orderBy('scores.type', $direction);
        } else {
            $query->orderBy('total_points', $direction);
        }

        $total = $query->get()->count();

        $data = $query
            ->offset($offset)
            ->limit($limit)
            ->get();

        return response()->json([
            'data'        => $data,
            'next_offset' => $offset + $limit,
            'has_more'    => ($offset + $limit) < $total,
        ]);
    }

}
