<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSurvey = Survey::count();

        $totalResponses = Response::count();

        $newResponsesToday = Response::whereDate('submitted_at', Carbon::today())->count();

        $lastResponseDate = Response::latest('submitted_at')->value('submitted_at');
        // Format tanggal jika ada, atau tampilkan "-"
        $lastResponseDateFormatted = $lastResponseDate ? Carbon::parse($lastResponseDate)->format('d M Y H:i') : '-';

        return view('survey-admin.dashboard.dashboard', compact(
            'totalSurvey',
            'totalResponses',
            'newResponsesToday',
            'lastResponseDateFormatted'
        ));
    }
}
