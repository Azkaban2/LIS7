<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Inventory::latest()->paginate(10);
        return view('admin.inventory.index', compact('items'));
    }

    public function create()
    {
        return view('admin.inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'purchased_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
        ]);

        $inventory = new Inventory();
        $inventory->name = $request->name;
        $inventory->category = $request->category;
        $inventory->quantity = $request->quantity;
        $inventory->description = $request->description;
        $inventory->purchased_date = $request->purchased_date;
        $inventory->price = $request->price;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/inventory'), $imageName);
            $inventory->image = $imageName;
        }

        $inventory->save();

        return redirect()->route('inventory.index')->with('success', 'Equipment added successfully.');
    }

    public function edit(Inventory $inventory)
    {
        return view('admin.inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'purchased_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $inventory->name = $request->name;
        $inventory->category = $request->category;
        $inventory->quantity = $request->quantity;
        $inventory->description = $request->description;
        $inventory->purchased_date = $request->purchased_date;
        $inventory->price = $request->price;

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image
            if ($inventory->image) {
                File::delete(public_path('uploads/inventory/' . $inventory->image));
            }

            // Save new image
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/inventory'), $imageName);
            $inventory->image = $imageName;
        }

        $inventory->save();

        return redirect()->route('inventory.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Inventory $inventory)
    {
        // Delete image file
        if ($inventory->image) {
            File::delete(public_path('uploads/inventory/' . $inventory->image));
        }

        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'Equipment deleted successfully.');
    }
}

