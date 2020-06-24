<?php

namespace App\Http\Controllers;

use App\Package;
use App\Plex;
use App\Traits\ChecksNightTime;
use App\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Pesapal;
use UniFi_API;
use NazmulB\MacAddressPhpLib\MacAddress;

class VoucherController extends Controller
{
    use ChecksNightTime;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payments.voucher');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'package' => 'required',
            'phone' => 'required',
        ]);
        $package = Package::find($request->package);
        $payment = new Payment;
        $payment->package_id = $package->id;
        $payment->phone_number = $request->phone;
        $payment->client_mac = $request->mac;
        $payment->ap_mac = $request->ap;
        $payment->transaction_ref = Pesapal::random_reference();
        $payment->amount = $package->amount;
        $payment->save();

        $details = array(
            'amount' => $package->amount,
            'description' => $package->description,
            'type' => 'MERCHANT',
            'phonenumber' => $request->phone,
            'reference' => $payment -> transaction_ref,
            'height'=>'700px',
            //'currency' => 'USD'
        );
        $iframe = Pesapal::makePayment($details);

        return view('payments.make', compact('iframe'));
    }

    public function verify(Request $request)
    {
//        dd($request);
        $code = $request->voucher;
        $voucher = Voucher::where('voucher_code', $code)->first();

        $mac = $request->mac;
        $ap = $request->ap;
//        dd($client);
        if ($voucher == null)
        {
            return Redirect::back()->with('error','The code you have entered is invalid!');
        }
        elseif($voucher->used == true)
        {
            return Redirect::back()->with('error','The code you have entered has already been used!');
        }
        else{
//            if (!$this->checkTime() && $voucher->package->quota_based == false)
//            {
//                return redirect('/')->with('status', 'You cannot use this voucher at the moment. Please try again at night');
//            }
            $voucher->used = true;
            $voucher->used_by = $mac;
            $voucher->used_at = $ap;
            $voucher->save();

//            Todo: enable this once landlords are added to the system
//            self::payLandlord($voucher->payment);

            $pack = $voucher->package;
            $mb = $pack->m_bytes;

            $up = $pack->up;
            $down = $pack->down;

            $minutes = $voucher->duration;

            $controller_user = config('app.unifi_username');
            $controller_pass = config('app.unifi_pass');
            $controller_url = config('app.unifi_url');
            $site = config('app.unifi_site');
            $version = config('app.unifi_version');
            $unifi = new UniFi_API\Client($controller_user, $controller_pass, $controller_url, $site, $version);
            $set_debug_mode   = $unifi->set_debug(false);
            $unifi->login();

//            $unifi->reconnect_sta($mac);
            $unifi->list_guests();
            $auth = $unifi->authorize_guest($mac, $minutes, $ap, $mb, $up, $down);

            if($auth == true)
            {
                $plex_auth = self::plex();

                return redirect('/')->with('status', 'You have successfully subscribed to the ' . $pack->name . ' that is ' . $pack->validity. '.');
//                return view('payments.authorized')->with('message', 'You have been granted access! Your plan expires in the next '.$hours.' hours.');
            }
            else{
                return redirect()->back();
            }
        }
    }
    public function getClientMac(){
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $arp=`arp -a $ipAddress`;
        $lines=explode("\n", $arp);
        $cols = preg_split('/\s+/', trim($lines[3]));
        return $cols[1];
    }

    public function service()
    {
        $controller_user = config('app.unifi_username');
        $controller_pass = config('app.unifi_pass');
        $controller_url = config('app.unifi_url');
        $site = config('app.unifi_site');
        $version = config('app.unifi_version');

        $unifi = new UniFi_API\Client($controller_user, $controller_pass, $controller_url, $site, $version);

        $unifi->login();
        $all = $unifi->list_guests();

        $mac1 = explode('-', $this->getClientMac());
        $mac = implode(':', $mac1);

//        $mac = '80:01:84:7a:31:74';
//        dd($mac);

        $clientele  = array_filter($all, function($obj)use($mac){
            if($obj->mac == $mac)
            {
                return true;
            }
            else{
                return false;
            }
        });


        return view('payments.services', compact('clients'));

    }

    public function RandomString()
    {
        $keySpace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pieces = [];
        $max = mb_strlen($keySpace, '8bit') - 1;
        for ($i = 0; $i < 10; ++$i) {
            $pieces []= $keySpace[random_int(0, $max)];
        }
        $random = implode('', $pieces);
        if(Voucher::where('voucher_code', $random)->first() !== null)
        {
            return $this->RandomString();
        }else{
            return $random;
        }
    }

    public function payLandlord($payment)
    {
        $landlord = $payment->access_point->user;
        $landlord->float += ($payment->amount * 0.03);
        $landlord->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voucher $voucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voucher $voucher)
    {
        //
    }

    public function plex()
    {
        $plex = new Plex();
        $response = $plex->authenticate();
        return $response;
    }
}
