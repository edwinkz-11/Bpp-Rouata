<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $documents = Document::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . addcslashes($request->input('q'), '%_\\') . '%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->input('category')))
            ->latest('id')->paginate(15)->withQueryString();

        return view('admin.documents.index', compact('documents'));
    }

    public function create(): View
    {
        return view('admin.documents.form', ['document' => new Document(['is_published' => true, 'year' => date('Y')])]);
    }

    public function store(DocumentRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('file');
        $data = array_merge($data, $this->storeFile($request->file('file')));
        $data['created_by'] = $request->user()->id;

        Document::create($data);

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen ditambahkan.');
    }

    public function edit(Document $document): View
    {
        return view('admin.documents.form', compact('document'));
    }

    public function update(DocumentRequest $request, Document $document): RedirectResponse
    {
        $data = $request->safe()->except('file');

        if ($file = $request->file('file')) {
            $old = $document->file_path;
            $data = array_merge($data, $this->storeFile($file), ['downloads' => 0]);
            Storage::disk(Document::DISK)->delete($old);
        }

        $document->update($data);

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen diperbarui.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        Gate::authorize('delete-record', $document);
        $document->delete();

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen dihapus.');
    }

    /** @return array{file_path:string,file_ext:string,file_size:int} */
    private function storeFile(UploadedFile $file): array
    {
        // Ekstensi dari nama file hanya dipakai bila ada di daftar putih. Isi file sudah divalidasi oleh aturan "mimes".
        $ext = strtolower($file->getClientOriginalExtension());
        $ext = in_array($ext, Document::EXTENSIONS, true) ? $ext : 'pdf';

        return [
            'file_path' => $file->storeAs('dokumen', Str::uuid() . '.' . $ext, Document::DISK),
            'file_ext' => $ext,
            'file_size' => $file->getSize(),
        ];
    }
}
