<?php

namespace App\Http\Controllers;

use App\Package;
use App\Voucher;
use Illuminate\Http\Request;
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
//    public function create($payment)
//    {
//        dd($payment);
//        $voucher = new Voucher;
//        $voucher->voucher_code = $this->RandomString();
//        $voucher->package_id = $payment->package_id;
//        $voucher->payment_id = $payment->id;
//        $voucher->duration = $payment->package->duration;
//        $voucher->save();
//
//        return redirect('/voucher')->with('status', 'Transaction has been completed succcessfully. You will receive a voucher code via sms shortly');
//    }

    public function verify(Request $request)
    {
        $voucher = Voucher::where('voucher_code', $request->voucher)->first();

        if ($voucher == null)
        {
            return redirect('/voucher')->with('status', 'The code you have entered is invalid');
        }
        else{
            if($voucher->used == true)
            {
                return redirect('/voucher')->with('status', 'The code you have entered has already been used');
            }
            else{
                $voucher->update([
                    'used' => true,
                ]);
                $mac = MacAddress::getMacAddress();
                $minutes = $voucher->duration;
                $hours = $minutes/60;

                //dd($mac);
                $controller_user = config('app.unifi_username');
                $controller_pass = config('app.unifi_pass');
                $controller_url = config('app.unifi_url');
                $site = config('app.unifi_site');
                $version = config('app.unifi_version');
                //dd($controller_url);
                $unifi = new UniFi_API\Client($controller_user, $controller_pass, $controller_url, $site, $version);
                //$set_debug_mode   = $unifi->set_debug(false);
                $unifi->login();
                $unifi->authorize_guest($mac, $minutes);

                return view('payments.authorized')->with('message', 'You have been granted access! Your plan expires in the next '.$hours.' hours.');
            }
        }
    }
    public function getClientMac(){
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        //dd($ipAddress);
        $macAddr='';
        $arp=`arp -a $ipAddress`;
        $lines=explode("\n", $arp);
        dd($lines);

        foreach($lines as $line){
            $cols=preg_split('/\s+/', trim($line));

            if ($cols[0]==$ipAddress){
                $macAddr=$cols[2];
            }
        }
        //dd($macAddr);
        return $macAddr;
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
