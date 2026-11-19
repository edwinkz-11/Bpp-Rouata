<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerRequest;
use App\Models\ExtensionWorker;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class WorkerController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(): View
    {
        return view('admin.workers.index', [
            'workers' => ExtensionWorker::orderBy('sort_order')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.workers.form', ['worker' => new ExtensionWorker()]);
    }

    public function store(WorkerRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('photo');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($file = $request->file('photo')) {
            $data['photo'] = $this->images->store($file, 'penyuluh');
        }

        ExtensionWorker::create($data);

        return redirect()->route('admin.workers.index')->with('success', 'Data penyuluh ditambahkan.');
    }

    public function edit(ExtensionWorker $worker): View
    {
        return view('admin.workers.form', ['worker' => $worker]);
    }

    public function update(WorkerRequest $request, ExtensionWorker $worker): RedirectResponse
    {
        $data = $request->safe()->except('photo');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['photo'] = $this->images->replace($request->file('photo'), $worker->photo, 'penyuluh');

        $worker->update($data);

        return redirect()->route('admin.workers.index')->with('success', 'Data penyuluh diperbarui.');
    }

    public function destroy(ExtensionWorker $worker): RedirectResponse
    {
        Gate::authorize('manage-structure');
        $worker->delete();

        return redirect()->route('admin.workers.index')->with('success', 'Data penyuluh dihapus.');
    }
}
