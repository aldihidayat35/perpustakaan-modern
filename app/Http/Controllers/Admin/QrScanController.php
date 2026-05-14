<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\View\View;

class QrScanController extends Controller
{
    public function index(): View
    {
        $members = Member::orderBy('name')->get();

        return view('admin.scan.index', compact('members'));
    }
}