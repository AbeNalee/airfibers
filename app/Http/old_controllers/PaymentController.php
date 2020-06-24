<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Package;
use App\Payment;
use Validator;
use Pesapal;
use Illuminate\Http\Request;
use App\Voucher;
use AfricasTalking\SDK\AfricasTalking;
use UniFi_API;
use Safaricom\Mpesa\Mpesa;

class PaymentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'package' => 'required',
            'phone2' => 'required',
        ]);
//        dd($request->mac);
        $package = Package::find($request->package);
        //dd($package->id);
        $payment = new Payment;
        $payment->package_id = $package->id;
        $payment->phone_number = $request->phone1 . $request->phone2;
        $payment->client_mac = $request->mac;
        $payment->ap_mac = $request->ap;
        $payment->transaction_ref = Pesapal::random_reference();
        $payment->amount = $package->amount;
        $payment->save();

        $quantity = $request->quantity || 1;
        $total = $package->amount * $quantity;

        $details = array(
            'amount' => $total,
            'description' => $package->description,
            'type' => 'MERCHANT',
            'phonenumber' => $request->phone1 . $request->phone2,
            'reference' => $payment->transaction_ref,
            'height' => '700px',
            //'currency' => 'USD'
        );
        $iframe = Pesapal::makePayment($details);

//        $this->sendMessage('Trial message', '0719320139');

        //dd($iframe);
        return view('payments.make', compact('iframe'));
    }

    public function test(Request $request)
    {
        $request->validate([
            'package' => 'required',
            'phone2' => 'required',
        ]);
//        dd($request->mac);
        $package = Package::find($request->package);
        //dd($package->id);
        $payment = new Payment;
        $payment->package_id = $package->id;
        $payment->phone_number = '254' . $request->phone2;
        $payment->client_mac = $request->mac;
        $payment->ap_mac = $request->ap;
        $payment->transaction_ref = self::mpesaReference();
        $payment->amount = $package->amount;
        $payment->save();

        $quantity = $request->quantity || 1;
        $total = $package->amount * $quantity;

        $mpesa = new Mpesa;
        $stkPush = $mpesa->STKPushSimulation(config('app.mpesa_shortcode'), config('app.mpesa_passkey'),
            'CustomerPayBillOnline', $total, $payment->phone_number, config('app.mpesa_shortcode'),
            $payment->phone_number,config('app.mpesa_callback'), $payment->transaction_ref);
//        $this->sendMessage('Trial message', '0719320139');

        dd($stkPush);
        $payment->update([
            'merchant_ref' => $stkPush->MerchantRequestID,
            'tracking_id' =>$stkPush->CheckoutRequestID,
            ''
        ]);

        return view('payments.make');
    }

    public function mpesaReference($prefix = 'PAY', $length = 8)
    {
        $keyspace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $str = '';

        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }

        return $prefix . $str;
    }




    public function paymentConfirmation(Request $request)
    {
        $trackingId = $request->tracking_id;
        $merchant_reference = $request->transactionRef;
        return $this->checkPaymentStatus($trackingId, $merchant_reference);
    }

    public function checkPaymentStatus($trackingId, $merchant_reference)
    {
        $status = Pesapal::getMerchantStatus($merchant_reference);
        //dd($elements);
        $payment = Payment::where('transaction_ref', $merchant_reference)->first();
//        dd($status);
        $payment->update([
            'status' => $status,
            'tracking_id' => $trackingId,
            'payment_method' => 'Pesapal',
        ]);
        $mac = $payment->client_mac;
        $ap = $payment->ap_mac;
        if ($status == 'PENDING') {
//            dd('You are here');
            return view('payments.home', compact('payment'));
        } elseif ($status == 'FAILED' || $status == 'INVALID') {
            return redirect('/')
                ->with('error', 'Sorry, the payment\'s ' . $status . '. Please, try again')
                ->with(compact('mac'))
                ->with(compact('ap'));
        } elseif ($status == 'COMPLETED') {
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
            return view('payments.choose', compact('payment'), compact('client'));
        }
    }

    public function first(Request $request)
    {
        $payment = Payment::find($request->payment);
        $minutes = $payment->package->duration;
        $hours = $minutes / 60;
        $mb = $payment->package->m_bytes;
        $mac = $payment->client_mac;
        $up = $payment->package->up;
        $down = $payment->package->down;
        $ap = $payment->ap_mac;

        if (is_null($payment->voucher) && is_null($payment->customer_id)) {
            $controller_user = config('app.unifi_username');
            $controller_pass = config('app.unifi_pass');
            $controller_url = config('app.unifi_url');
            $site = config('app.unifi_site');
            $version = config('app.unifi_version');
            //dd($controller_url);
            $unifi = new UniFi_API\Client($controller_user, $controller_pass, $controller_url, $site, $version);
            $set_debug_mode = $unifi->set_debug(false);
            $unifi->login();
            $auth = $unifi->authorize_guest($mac, $minutes, $ap, $mb, $up, $down);

            if ($auth == true) {
                $this->saveCustomer($payment);
                return redirect('/')->with('status', 'You have successfully subscribed to the ' . $payment->package->name . ' that is ' . $payment->package->validity. '.');
//                return view('payments.authorized')->with('message', 'You have been granted access! Your plan expires in the next '.$hours.' hours.');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect('/')
                ->with('error', 'Transaction has already been processed!');
        }
    }

    public function second(Request $request)
    {
        $payment = Payment::find($request->payment);

        if (is_null($payment->voucher) && is_null($payment->customer_id)) {
            $voucher = new Voucher;
            $voucher->voucher_code = $this->RandomString();
            $voucher->package_id = $payment->package_id;
            $voucher->payment_id = $payment->id;
            $voucher->duration = $payment->package->duration;
            $voucher->save();

            $this->sendMessage($voucher->voucher_code, $voucher->payment->phone_number);

            $this->saveCustomer($payment);

            return redirect('/')->with('status', 'You will receive a voucher code via sms shortly. Connect. Explore. Experience!');

//            return view('payments.authorized')
//                ->with('message', 'You will receive a voucher code via sms shortly. Connect. Explore. Experience!');
        } else {
            return redirect('/')->with('error', 'Transaction has already been processed!');

//            return view('payments.authorized')
//                ->with('message', 'Transaction has already been processed!');
        }

    }

    public function saveCustomer($payment)
    {
        $customer = Customer::firstOrCreate([
            'phone_number' => $payment->phone_number,
        ], [
            'name' => '',
        ]);

        $pay = Payment::find($payment->id);
        $pay->customer_id = $customer->id;
        $pay->save();

    }

    public function sendMessage($voucherCode, $phone)
    {
        $username = config('app.africastalking_username');
        $key = config('app.africastalking_key');
        //dd($username);

        $AT = new AfricasTalking($username, $key);

        $sms = $AT->sms();

        return $sms->send([
            'to' => $phone,
            'message' => 'Your AIRFibers voucher code is ' . $voucherCode . ' Connect. Explore. Experience!',
            'from' => 'AIRFibers'
        ]);
    }

    public function RandomString()
    {
        $keySpace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pieces = [];
        $max = mb_strlen($keySpace, '8bit') - 1;
        for ($i = 0; $i < 10; ++$i) {
            $pieces [] = $keySpace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    protected function GetClientMac()
    {
        $macAddr = false;
        $arp = `arp -n`;
        $lines = explode("\n", $arp);

        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));

            if ($cols[0] == $_SERVER['REMOTE_ADDR']) {
                $macAddr = $cols[2];
            }
        }
//        dd($macAddr);
        return $macAddr;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function paymentsuccess(Request $request)//just tells u payment has gone thru..but not confirmed
    {
        $trackingid = $request->input('tracking_id');
        $ref = $request->input('merchant_reference');
//        dd($ref);

        $payment = Payment::where('transaction_ref', $ref)->first();
//        dd($payment);
        $payment->update([
            'tracking_id' => $trackingid,
            'status' => 'PENDING',
        ]);
        //dd($payment);
        return view('payments.home', compact('payment'));
    }

    public function safaricom(Request $request)
    {
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
