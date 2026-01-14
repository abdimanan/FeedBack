<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FeedbackLink;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PublicFeedbackController extends Controller
{
    /**
     * Show the feedback form.
     */
    public function show(string $token): View|RedirectResponse
    {
        $feedbackLink = FeedbackLink::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->with('project.client')
            ->first();

        if (!$feedbackLink) {
            return redirect('/')->with('error', 'This feedback link is invalid or has expired.');
        }

        if (!$feedbackLink->project) {
            return redirect('/')->with('error', 'Project not found for this feedback link.');
        }

        return view('feedback.public-form', [
            'feedbackLink' => $feedbackLink,
            'project' => $feedbackLink->project,
        ]);
    }

    /**
     * Store the feedback submission.
     */
    public function store(Request $request, string $token): View|RedirectResponse
    {
        $feedbackLink = FeedbackLink::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$feedbackLink) {
            return redirect('/')->with('error', 'This feedback link is invalid or has expired.');
        }

        $validated = $request->validate([
            'statement_1_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'statement_2_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'statement_3_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'likes_text' => ['nullable', 'string'],
            'dislikes_text' => ['nullable', 'string'],
            'overall_rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        Feedback::create([
            'project_id' => $feedbackLink->project_id,
            'statement_1_rating' => $validated['statement_1_rating'],
            'statement_2_rating' => $validated['statement_2_rating'],
            'statement_3_rating' => $validated['statement_3_rating'],
            'likes_text' => $validated['likes_text'],
            'dislikes_text' => $validated['dislikes_text'],
            'overall_rating' => $validated['overall_rating'],
            'created_at' => now(),
        ]);

        $feedbackLink->update(['used_at' => now()]);

        return view('feedback.thank-you');
    }
}
