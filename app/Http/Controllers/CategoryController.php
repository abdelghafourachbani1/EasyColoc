<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Colocation $colocation)
    {
        $this->authorizeOwner($colocation);

        $colocation->load('categories');

        return view('categories.index', compact('colocation'));
    }

    public function store(Request $request, Colocation $colocation)
    {
        $this->authorizeOwner($colocation);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $colocation->categories()->create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    public function destroy(Category $category)
    {
        $this->authorizeOwner($category->colocation);

        if ($category->expenses()->exists()) {
            return back()->with('error', 'Cannot delete category that has expenses. Please reassign the expenses first.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }

    private function authorizeOwner(Colocation $colocation)
    {
        if (auth()->id() !== $colocation->owner_id) {
            abort(403, 'Only the colocation owner can manage categories.');
        }
    }
}
