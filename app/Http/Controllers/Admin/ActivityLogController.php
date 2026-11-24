<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::query()
            ->when($request->filled('action'), fn ($q) => $q->where('action', $request->input('action')))
            ->when($request->filled('type'), fn ($q) => $q->where('subject_type', $request->input('type')))
            ->latest('id')->paginate(30)->withQueryString();

        return view('admin.logs.index', [
            'logs' => $logs,
            'types' => ActivityLog::query()->distinct()->orderBy('subject_type')->pluck('subject_type'),
        ]);
    }
}
