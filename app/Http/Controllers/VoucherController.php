<?php

namespace App\Http\Controllers;

use App\Package;
use App\Voucher;
use Illuminate\Http\Request;
use Pesapal;
use UniFi_API;
use NazmulB\MacAddressPhpLib\MacAddress;

class VoucherController extends Controller
{
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
            return view('payments.voucher')->with('status', 'The code you have entered is invalid')
                ->with(compact('mac'))
                ->with(compact('ap'));
//            return back()->withInput()->with('status', 'The code you have entered is invalid');
        }
        elseif($voucher->used == true)
        {
//            dd($client);
            return view('payments.voucher')->with('status', 'The code you have entered has already been used')
                ->with(compact('mac'))
                ->with(compact('ap'));
//            return back()->withInput()->with('status', 'The code you have entered has already been used');
        }
        else{
            $voucher->used = true;
            $voucher->save();

            $pack = $voucher->package;
            $mb = $pack->m_bytes;

            $minutes = $voucher->duration;
            $hours = $minutes/60;

//            dd($mac);
            $controller_user = config('app.unifi_username');
            $controller_pass = config('app.unifi_pass');
            $controller_url = config('app.unifi_url');
            $site = config('app.unifi_site');
            $version = config('app.unifi_version');
            $unifi = new UniFi_API\Client($controller_user, $controller_pass, $controller_url, $site, $version);
            $set_debug_mode   = $unifi->set_debug(false);
            $unifi->login();
            $auth = $unifi->authorize_guest($mac, $minutes, $ap, $mb);

            if($auth == true)
            {
                return redirect('/self-service');
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

        $clients = [current($clientele)];

//        dd($clients);
        return view('payments.service', compact('clients'));

    }

    public function RandomString()
    {
        $keySpace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pieces = [];
        $max = mb_strlen($keySpace, '8bit') - 1;
        for ($i = 0; $i < 7; ++$i) {
            $pieces []= $keySpace[random_int(0, $max)];
        }
        return implode('', $pieces);
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
}
