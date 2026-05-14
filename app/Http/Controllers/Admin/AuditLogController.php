<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::with('user');

        if ($action = $request->string('action')->toString()) {
            $query->where('action', $action);
        }

        if ($search = $request->string('search')->toString()) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($type = $request->string('type')->toString()) {
            $query->where('model_type', 'like', "%{$type}%");
        }

        $logs = $query->latest()->paginate(30)->withQueryString();

        return view('admin.audit-logs.index', compact('logs'));
    }
}