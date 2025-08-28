<?php

namespace App\Http\Controllers;

use Dom\Attr;
use Illuminate\Http\Request;
use App\Facades\LoadHistory;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function load(Request $request)
    {
        $facade = new LoadHistory(Auth::id());

        $selectedMonth = $request->input('month', now()->format('Y-m'));
        [$year, $month] = explode('-', $selectedMonth);

        $records = $facade->load($year, $month);

        //dd(1);

        return view('history', compact('records', 'selectedMonth')); 
    
    }

}
