<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;

class AgentController extends Controller
{
    public function index()
    {
        return view('agent.agent');
    }
    public function make()
    {
        $unlimited_packs = Package::where('quota_based', false)->get();
        $daily_packs = Package::where('quota_based', true)->where('duration', 1440)->get();
        $weekly_packs = Package::where('quota_based', true)->where('duration', 10080)->get();
        $monthly_packs = Package::where('quota_based', true)->where('duration', 43200)->get();
        return view('agent.packages')
            ->with(compact('unlimited_packs'))
            ->with(compact('daily_packs'))
            ->with(compact('weekly_packs'))
            ->with(compact('monthly_packs'));
    }
}
