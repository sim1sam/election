<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $voters = Voter::latest()->paginate(20);
        return view('admin.voters.index', compact('voters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.voters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'voter_number' => 'required|string|max:255|unique:voters,voter_number',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'polling_center_name' => 'nullable|string|max:255',
            'ward_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        Voter::create($validated);

        return redirect()->route('admin.voters.index')
            ->with('success', 'Voter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Voter $voter)
    {
        return view('admin.voters.show', compact('voter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voter $voter)
    {
        return view('admin.voters.edit', compact('voter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voter $voter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'voter_number' => 'required|string|max:255|unique:voters,voter_number,' . $voter->id,
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'polling_center_name' => 'nullable|string|max:255',
            'ward_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        $voter->update($validated);

        return redirect()->route('admin.voters.index')
            ->with('success', 'Voter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voter $voter)
    {
        $voter->delete();

        return redirect()->route('admin.voters.index')
            ->with('success', 'Voter deleted successfully.');
    }
}
