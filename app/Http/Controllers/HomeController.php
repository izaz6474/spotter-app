<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\HomeLoader;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $selectedTab = (int) $request->input('selectedTab', 1);
        $selectedFilter = (int) $request->input('selectedFilter', 1); 
        $searchText = (string) $request->input('searchText', '');
        
        //dd($selectedFilter);

        $user_id = Auth::id();

        // Use the Facade
        $homeLoader = new HomeLoader($selectedTab, $selectedFilter, $searchText, $user_id);
        $data = $homeLoader->load();

        return view('home', $data);
    }
}
