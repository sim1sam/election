<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use Illuminate\Http\Request;

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
        $format = $request->input('format', '1');
        
        $rules = [
            'format' => 'required|in:1,2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
        
        // Format 1 requires message, allows icon_image and subtitle
        if ($format == '1') {
            $rules['message'] = 'required|string';
            $rules['icon_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['subtitle'] = 'nullable|string|max:255';
        }
        // Format 2 only requires image and title
        
        $validated = $request->validate($rules);
        $validated['format'] = $format;

        // Handle image upload - save directly to public/popups
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('popups'), $imageName);
            $validated['image'] = 'popups/' . $imageName;
        }

        // Handle icon image upload - save directly to public/popups
        if ($request->hasFile('icon_image')) {
            $iconImage = $request->file('icon_image');
            $iconName = time() . '_' . uniqid() . '.' . $iconImage->getClientOriginalExtension();
            $iconImage->move(public_path('popups'), $iconName);
            $validated['icon_image'] = 'popups/' . $iconName;
        }

        $validated['is_active'] = $request->has('is_active');
        
        // Set default values for format 2 - only set if not already in validated
        if ($format == '2') {
            if (!isset($validated['message'])) {
                $validated['message'] = null;
            }
            if (!isset($validated['subtitle'])) {
                $validated['subtitle'] = null;
            }
            if (!isset($validated['icon_image'])) {
                $validated['icon_image'] = null;
            }
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
        $format = $request->input('format', $popup->format ?? '1');
        
        $rules = [
            'format' => 'required|in:1,2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
        
        // Format 1 requires message, allows icon_image and subtitle
        if ($format == '1') {
            $rules['message'] = 'required|string';
            $rules['icon_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['subtitle'] = 'nullable|string|max:255';
        }
        
        $validated = $request->validate($rules);
        $validated['format'] = $format;

        // Handle image upload - save directly to public/popups
        if ($request->hasFile('image')) {
            // Delete old image
            if ($popup->image && file_exists(public_path($popup->image))) {
                unlink(public_path($popup->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('popups'), $imageName);
            $validated['image'] = 'popups/' . $imageName;
        }

        // Handle icon image upload - save directly to public/popups
        if ($request->hasFile('icon_image')) {
            // Delete old icon image
            if ($popup->icon_image && file_exists(public_path($popup->icon_image))) {
                unlink(public_path($popup->icon_image));
            }
            $iconImage = $request->file('icon_image');
            $iconName = time() . '_' . uniqid() . '.' . $iconImage->getClientOriginalExtension();
            $iconImage->move(public_path('popups'), $iconName);
            $validated['icon_image'] = 'popups/' . $iconName;
        }

        $validated['is_active'] = $request->has('is_active');
        
        // Set default values for format 2 - ensure null values for fields not used in Format 2
        if ($format == '2') {
            // Only set to null if not already set (to avoid overwriting if somehow included)
            if (!array_key_exists('message', $validated)) {
                $validated['message'] = null;
            }
            if (!array_key_exists('subtitle', $validated)) {
                $validated['subtitle'] = null;
            }
            // Only delete icon_image if format changed from 1 to 2
            if ($popup->format == '1' && $format == '2' && $popup->icon_image && file_exists(public_path($popup->icon_image))) {
                unlink(public_path($popup->icon_image));
            }
            if (!array_key_exists('icon_image', $validated)) {
                $validated['icon_image'] = null;
            }
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
        // Delete images from public folder
        if ($popup->image && file_exists(public_path($popup->image))) {
            unlink(public_path($popup->image));
        }
        if ($popup->icon_image && file_exists(public_path($popup->icon_image))) {
            unlink(public_path($popup->icon_image));
        }

        $popup->delete();

        return redirect()->route('admin.popups.index')
            ->with('success', 'Popup deleted successfully.');
    }
}
