<?php

namespace App\Http\Controllers;

use App\MacAddress;
use App\Package;
use App\Traits\ChecksNightTime;
use function foo\func;
use Illuminate\Http\Request;
use function Sodium\compare;
use UniFi_API;
use Carbon\Carbon;

class PackagesController extends Controller
{
    use ChecksNightTime;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->query('id'))
        {
            $mac = $request->query('id');
        }
        else{
            $mac = $this->getClientMac();
        }

        if($request->query('ap')){
            $ap = $request->query('ap');
        }else{
            $ap = ' ';
        }

//        dd($mac);
        $controller_user = config('app.unifi_username');
        $controller_pass = config('app.unifi_pass');
        $controller_url = config('app.unifi_url');
        $site = config('app.unifi_site');
        $version = config('app.unifi_version');

        $unifi = new UniFi_API\Client($controller_user, $controller_pass, $controller_url, $site, $version);

        $unifi->login();
        $all = $unifi->list_guests();

        $clientele = array_filter($all, function ($obj) use ($mac) {
            if ($obj->mac == $mac) {
                return true;
            } else {
                return false;
            }
        });


        $clients = [current($clientele)];
        $client = $clients[0];

//        $set = isset($client);
//        dd($set);


        return view('payments.services')
            ->with(compact('client'))
            ->with(compact('mac'))
            ->with(compact('ap'));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getClientMac()
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $macAddr = false;

        $arp = `arp -a $ipAddress`;
        $lines = explode("\n", $arp);

        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));
            if ($cols[0] == $ipAddress) {
                $macAddr = $cols[1];
            }
        }
        $macArray = explode('-', $macAddr);
        $macAddress = implode(':', $macArray);

        return $macAddress;
    }

    public function store(Request $request)
    {
        $mac = $request->query('id');
        $ap = $request->query('ap');
        if($request->query('path') == 'online')
        {
            return self::online($mac, $ap);
        }else{
            return self::offline($mac, $ap);
        }
    }

    public function online($mac, $ap)
    {
        $controller_user = config('app.unifi_username');
        $controller_pass = config('app.unifi_pass');
        $controller_url = config('app.unifi_url');
        $site = config('app.unifi_site');
        $version = config('app.unifi_version');

        $unifi = new UniFi_API\Client($controller_user, $controller_pass, $controller_url, $site, $version);

        $unifi->login();
        $all = $unifi->list_guests();

        $clientele = array_filter($all, function ($obj) use ($mac) {
            if ($obj->mac == $mac) {
                return true;
            } else {
                return false;
            }
        });

        $clients = [current($clientele)];
        $client = $clients[0];

//        $dt = self::checkWeekend();
        $night = $this->checkTime();
        $holiday = self::checkHoliday();
        $hols = 'Holiday';

        $unlimited_packs = Package::where('quota_based', false)->get();
        $night_packs = Package::where('quota_based', false)->whereIn('duration', [60, 180])->get();
        $daily_packs = Package::where('quota_based', true)->where('duration', 1440)->get();
        $weekly_packs = Package::where('quota_based', true)->where('duration', 10080)->get();
        $monthly_packs = Package::where('quota_based', true)->where('duration', 43200)->get();
//        $weekend = Package::where('quota_based', false)->where('duration','<=', 1440)->get();
        $holiday_packs = Package::where('description', 'LIKE', '%'.$hols.'%')->get();
        return view('packages.page')
            ->with(compact('unlimited_packs'))
            ->with(compact('night_packs'))
            ->with(compact('daily_packs'))
            ->with(compact('weekly_packs'))
            ->with(compact('monthly_packs'))
            ->with(compact('weekend'))
            ->with(compact('mac'))
            ->with(compact('ap'))
            ->with(compact('client'))
            ->with(compact('holiday'))
            ->with(compact('holiday_packs'))
            ->with(compact('night'));
    }

    public function storeUnlimitedOnly(Request $request)
    {
        $mac = $request->query('id');
        $ap = $request->query('ap');

        $controller_user = config('app.unifi_username');
        $controller_pass = config('app.unifi_pass');
        $controller_url = config('app.unifi_url');
        $site = config('app.unifi_site');
        $version = config('app.unifi_version');

        $unifi = new UniFi_API\Client($controller_user, $controller_pass, $controller_url, $site, $version);

        $unifi->login();
        $all = $unifi->list_guests();

        $clientele = array_filter($all, function ($obj) use ($mac) {
            if ($obj->mac == $mac) {
                return true;
            } else {
                return false;
            }
        });

        $clients = [current($clientele)];
        $client = $clients[0];

//        $dt = self::checkWeekend();
        $night = $this->checkTime();
        $holiday = self::checkHoliday();
        $hols = 'Holiday';

        $unlimited_packs = Package::where('quota_based', false)->get();
        return view('packages.page')
            ->with(compact('unlimited_packs'))
            ->with(compact('mac'))
            ->with(compact('ap'))
            ->with(compact('client'))
            ->with(compact('holiday'))
            ->with(compact('night'));
    }

    public function offline($mac, $ap)
    {
        $controller_user = config('app.unifi_username');
        $controller_pass = config('app.unifi_pass');
        $controller_url = config('app.unifi_url');
        $site = config('app.unifi_site');
        $version = config('app.unifi_version');

        $unifi = new UniFi_API\Client($controller_user, $controller_pass, $controller_url, $site, $version);

        $unifi->login();
        $all = $unifi->list_guests();

        $clientele = array_filter($all, function ($obj) use ($mac) {
            if ($obj->mac == $mac) {
                return true;
            } else {
                return false;
            }
        });

        $clients = [current($clientele)];
        $client = $clients[0];

        $unlimited_packs = Package::where('quota_based', false)->get();
        $daily_packs = Package::where('quota_based', true)->where('duration', 1440)->get();
        $weekly_packs = Package::where('quota_based', true)->where('duration', 10080)->get();
        $monthly_packs = Package::where('quota_based', true)->where('duration', 43200)->get();
        return view('offline')
            ->with(compact('unlimited_packs'))
            ->with(compact('daily_packs'))
            ->with(compact('weekly_packs'))
            ->with(compact('monthly_packs'))
            ->with(compact('mac'))
            ->with(compact('ap'))
            ->with(compact('client'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Package $packages
     * @return \Illuminate\Http\Response
     */
    public function show(Package $packages)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Package $packages
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $packages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Package $packages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $packages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Package $packages
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $packages)
    {
        //
    }

    public function checkWeekend()
    {
        $date = Carbon::now();
        if($date->isFriday() || $date->isSaturday() || $date->isSunday())
        {
            $dt = true;
        }else{
            $dt = false;
        }
        return $dt;
    }

    public function checkHoliday()
    {
        if(Carbon::now()->between(Carbon::create(2019, 12, 21, 0), Carbon::create(2020, 1, 10, 24))){
            $holiday = true;
        }else{
            $holiday = false;
        }
        return $holiday;
    }
}
