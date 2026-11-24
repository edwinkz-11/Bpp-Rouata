<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityPhoto;
use App\Models\Commodity;
use App\Models\ContactMessage;
use App\Models\Document;
use App\Models\Location;
use App\Models\MapRoute;
use App\Models\PageView;
use App\Models\Post;
use App\Models\Village;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            ['label' => 'Total Kegiatan', 'value' => Activity::count(), 'icon' => 'bi-calendar-event', 'route' => 'admin.activities.index'],
            ['label' => 'Foto Dokumentasi', 'value' => ActivityPhoto::count(), 'icon' => 'bi-images', 'route' => 'admin.gallery.index'],
            ['label' => 'Total Komoditas', 'value' => Commodity::count(), 'icon' => 'bi-flower2', 'route' => 'admin.commodities.index'],
            ['label' => 'Total Desa', 'value' => Village::count(), 'icon' => 'bi-houses', 'route' => 'admin.villages.index'],
            ['label' => 'Total Lokasi', 'value' => Location::count(), 'icon' => 'bi-geo-alt', 'route' => 'admin.locations.index'],
            ['label' => 'Jalur Terpetakan', 'value' => MapRoute::count(), 'icon' => 'bi-signpost-split', 'route' => 'admin.routes.index'],
            ['label' => 'Berita BPP', 'value' => Post::count(), 'icon' => 'bi-newspaper', 'route' => 'admin.posts.index'],
            ['label' => 'Dokumen', 'value' => Document::count(), 'icon' => 'bi-folder2-open', 'route' => 'admin.documents.index'],
            ['label' => 'Pesan Belum Dibaca', 'value' => ContactMessage::unreadCount(), 'icon' => 'bi-inbox', 'route' => 'admin.messages.index'],
        ];

        $statuses = ['verified' => 'Terverifikasi', 'unverified' => 'Belum Terverifikasi', 'draft' => 'Draft'];

        $mapping = [
            'Lokasi' => Location::selectRaw('verification_status as s, count(*) as c')->groupBy('verification_status')->pluck('c', 's'),
            'Jalur' => MapRoute::selectRaw('verification_status as s, count(*) as c')->groupBy('verification_status')->pluck('c', 's'),
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'statuses' => $statuses,
            'mapping' => $mapping,
            'latest' => Activity::with('category')->latest()->take(6)->get(),
            'visits' => PageView::summary(),
            'topPages' => PageView::top(5),
        ]);
    }
}
