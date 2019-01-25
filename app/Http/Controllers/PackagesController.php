<?php

namespace App\Http\Controllers;

use App\MacAddress;
use App\Package;
use Illuminate\Http\Request;
use function Sodium\compare;

class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd($request->query('ap'));

        $packs = Package::all();
        return view('welcome')->with('packs', $packs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function voucher(Request $request)
    {
        $mac = $request->mac;
        $ap = $request->ap;
//        dd($ap);
        $address = new MacAddress;
        $address->mac = $mac;
        $address->ap_mac = $ap;
        $address->save();

//        dd($request->ap);
        return view('payments.voucher')->with(compact('mac'))
            ->with(compact('ap'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mac = $request->query('id');
        $ap = $request->query('ap');

//        $mac = '1';
//        $ap = '2';
        $unlimited_packs = Package::where('quota_based', false)->get();
        $daily_packs = Package::where('quota_based', true)->where('duration', 1440)->get();
        $weekly_packs = Package::where('quota_based', true)->where('duration', 10080)->get();
        $monthly_packs = Package::where('quota_based', true)->where('duration', 43200)->get();
        return view('welcome')
            ->with(compact('unlimited_packs'))
            ->with(compact('daily_packs'))
            ->with(compact('weekly_packs'))
            ->with(compact('monthly_packs'))
            ->with(compact('mac'))
            ->with(compact('ap'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Package  $packages
     * @return \Illuminate\Http\Response
     */
    public function show(Package $packages)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Package  $packages
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $packages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Package  $packages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $packages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Package  $packages
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $packages)
    {
        //
    }
}
