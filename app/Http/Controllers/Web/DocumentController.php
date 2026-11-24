<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q'));

        $documents = Document::published()
            ->when($request->filled('kategori'), fn ($q) => $q->where('category', $request->input('kategori')))
            ->when($request->filled('tahun'), fn ($q) => $q->where('year', $request->integer('tahun')))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $q->where(fn ($w) => $w->where('title', 'like', $like)->orWhere('description', 'like', $like));
            })
            ->orderByDesc('year')->orderByDesc('id')
            ->paginate(12)->withQueryString();

        return view('web.documents', [
            'documents' => $documents,
            'categories' => Document::CATEGORIES,
            'years' => Document::published()->whereNotNull('year')->distinct()->orderByDesc('year')->pluck('year'),
            'search' => $search,
        ]);
    }

    public function download(Document $document): StreamedResponse
    {
        abort_unless($document->is_published, 404);
        abort_unless(Storage::disk(Document::DISK)->exists($document->file_path), 404);

        $document->increment('downloads');

        return Storage::disk(Document::DISK)->download($document->file_path, $document->download_name);
    }
}
