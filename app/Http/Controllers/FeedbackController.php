<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbacks = Feedback::with('project.client')->latest('created_at')->paginate(15);

        return view('feedback.index', compact('feedbacks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('project.client');

        return response()->json([
            'project' => $feedback->project->name,
            'client' => $feedback->project->client->name,
            'statement_1_rating' => $feedback->statement_1_rating,
            'statement_2_rating' => $feedback->statement_2_rating,
            'statement_3_rating' => $feedback->statement_3_rating,
            'likes_text' => $feedback->likes_text,
            'dislikes_text' => $feedback->dislikes_text,
            'overall_rating' => $feedback->overall_rating,
            'created_at' => $feedback->created_at->format('M d, Y'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
