<?php

namespace App\Http\Controllers;

use App\Models\FeedbackLink;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('client')->latest()->paginate(15);

        return view('projects.index', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('status', 'Project created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('status', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('status', 'Project deleted successfully.');
    }

    /**
     * Generate a feedback link for the project.
     */
    public function generateFeedbackLink(Project $project)
    {
        $token = Str::uuid()->toString();
        $expiresAt = now()->addDays(7);

        $feedbackLink = FeedbackLink::create([
            'project_id' => $project->id,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $url = route('feedback.form', ['token' => $token], absolute: true);

        return redirect()->route('projects.index')
            ->with('status', 'Feedback link generated successfully!')
            ->with('feedback_url', $url)
            ->with('project_id', $project->id);
    }
}
