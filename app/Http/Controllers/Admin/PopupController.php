<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PopupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $popups = Popup::latest()->get();
        return view('admin.popups.index', compact('popups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.popups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'message' => 'required|string',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('popups', 'public');
        }

        // Handle icon image upload
        if ($request->hasFile('icon_image')) {
            $validated['icon_image'] = $request->file('icon_image')->store('popups', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        // If this popup is being set as active, deactivate all others
        if ($validated['is_active']) {
            Popup::ensureOnlyOneActive();
        }

        Popup::create($validated);

        return redirect()->route('admin.popups.index')
            ->with('success', 'Popup created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Popup $popup)
    {
        return view('admin.popups.show', compact('popup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Popup $popup)
    {
        return view('admin.popups.edit', compact('popup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Popup $popup)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'message' => 'required|string',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($popup->image) {
                Storage::disk('public')->delete($popup->image);
            }
            $validated['image'] = $request->file('image')->store('popups', 'public');
        }

        // Handle icon image upload
        if ($request->hasFile('icon_image')) {
            // Delete old icon image
            if ($popup->icon_image) {
                Storage::disk('public')->delete($popup->icon_image);
            }
            $validated['icon_image'] = $request->file('icon_image')->store('popups', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        // If this popup is being set as active, deactivate all others
        if ($validated['is_active']) {
            Popup::ensureOnlyOneActive($popup->id);
        }

        $popup->update($validated);

        return redirect()->route('admin.popups.index')
            ->with('success', 'Popup updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Popup $popup)
    {
        // Delete images
        if ($popup->image) {
            Storage::disk('public')->delete($popup->image);
        }
        if ($popup->icon_image) {
            Storage::disk('public')->delete($popup->icon_image);
        }

        $popup->delete();

        return redirect()->route('admin.popups.index')
            ->with('success', 'Popup deleted successfully.');
    }
}
