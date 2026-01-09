<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ReportController;

class ReportUIController extends Controller
{
    public function index(Request $request, ReportController $api)
    {
        if ($request->year && $request->month) {
            $report = $api->monthly($request)->getData();
            return view('report.index', compact('report'));
        }

        return view('report.index');
    }

    public function history(Request $request, ReportController $api)
    {
        $history = $api->history($request)->getData();
        return view('report.history', compact('history'));
    }
}
