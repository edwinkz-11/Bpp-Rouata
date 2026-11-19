<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\ActivityCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => ActivityCategory::withCount('activities')->orderBy('name')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['category' => new ActivityCategory()]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        ActivityCategory::create($request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(ActivityCategory $category): View
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(CategoryRequest $request, ActivityCategory $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(ActivityCategory $category): RedirectResponse
    {
        Gate::authorize('manage-structure');
        $category->delete(); // kegiatan terkait otomatis menjadi "tanpa kategori" (FK set null)

        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus.');
    }
}
