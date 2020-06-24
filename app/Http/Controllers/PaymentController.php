<?php

namespace App\Http\Controllers;

use App\Customer;
use App\MacAddress;
use App\Package;
use App\Payment;
use App\Plex;
use App\Traits\ChecksNightTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Pesapal;
use Illuminate\Http\Request;
use App\Voucher;
use AfricasTalking\SDK\AfricasTalking;
use UniFi_API;
use Safaricom\Mpesa\Mpesa;

class PaymentController extends Controller
{
    use ChecksNightTime;
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        dd($request);
        $validator = Validator::make($request->all, [
            'package' => 'required',
            'phone2' => 'required',
        ], [
            'required' => 'Please select a package before proceeding',
        ]);

        dd($validator);
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

    public function online(Request $request)
    {
        $validator = Validator::make(
            $request->all()
            , [
            'package' => 'required',
            'phone' => 'required',
            'purpose' => 'required',
            ], [
            'purpose.required' => 'Please choose if you want access on this device or a voucher code to be used later before proceeding',
            'package.required' => 'Please select a package before proceeding',
            'phone.required' => 'Make sure you have entered your Mpesa number'
            ])->validate();

        $phone = self::formatPhone($request->phone);

        $package = Package::find($request->package);

//        if (!$this->checkTime() && $package->quota_based == false)
//        {
//            return redirect('/')->with('status', 'You cannot purchase this package at the moment. Please try again at night');
//        }

        $holiday = self::checkHoliday();
        $packAllowed = self::checkPackage($package);
        if($holiday == true && $packAllowed == 'isDaily')
        {
            if($package->amount == 15){
                $amount = 10;
            }else{
                $amount = $package->amount / 2;
            }
        }else{
            $amount = $package->amount;
        }

        $mpesa = new Mpesa;
        $stkPush = json_decode($mpesa->STKPushSimulation(config('app.mpesa_shortcode'), config('app.mpesa_passkey'),
            'CustomerBuyGoodsOnline', $amount, $phone, config('app.mpesa_till'),
            $phone, config('app.mpesa_callback'), 'AIRFibers'));

        if (isset($stkPush->ResponseCode) && $stkPush->ResponseCode == '0') {

            $payment = new Payment;
            $payment->package_id = $package->id;
            $payment->phone_number = $phone;
            $payment->client_mac = $request->mac;
            $payment->ap_mac = $request->ap;
            $payment->amount = $package->amount;
            $payment->payment_method = 'Mpesa STK Push';
            $payment->status = 'NEW';
            $payment->purpose = $request->purpose;
            $payment->merchant_req_id = $stkPush->MerchantRequestID;
            $payment->checkout_req_id = $stkPush->CheckoutRequestID;
            $payment->save();

            if($payment->purpose == 'ACCESS'){
                return redirect('/')->with('status', 'Lipa na M-PESA Online has been initiated on your phone.
                Please enter your M-PESA PIN and on successful payment, you can refresh this page to confirm your balance.');
//                return redirect()->route('payment', ['id' => $payment->merchant_req_id])
//                    ->with(compact('payment'));
            }else{
                return redirect('/')->with('status', 'Lipa na M-PESA Online has been initiated on your phone.
                Please enter your M-PESA PIN and on successful payment, you will receive your voucher code via SMS');
            }


        } else {
            $message = $stkPush->errorMessage;
            return redirect()->back()
                ->with('error', 'Safaricom has a temporary glitch. '. $message);
        }
    }

    public function offline(Request $request)
    {

        $validator = Validator::make(
            $request->all()
            , [
            'package' => 'required',
            'code' => 'required',
            'purpose' => 'required',
        ], [
            'purpose.required' => 'Please choose if you want access on this device or a voucher code to be used later before proceeding',
            'package.required' => 'Please select a package before proceeding',
            'code.required' => 'Please enter your Mpesa confirmation code'
        ])->validate();

        $exists = self::codeExists($request->code);

        if ($exists !== false) {
            $package = Package::find($request->package);

            $pack = self::mapPackage($exists->trans_amount, $package);

            if ($pack == true) {
                $payment = new Payment;
                $payment->package_id = $package->id;
                $payment->phone_number = $exists->msisdn;
                $payment->client_mac = $request->mac;
                $payment->ap_mac = $request->ap;
                $payment->amount = $package->amount;
                $payment->payment_method = 'Mpesa C2B';
                $payment->status = 'COMPLETED';
                $payment->mpesa_id = $exists->id;
                $payment->mpesa_code = $exists->tran_id;
                $payment->purpose = $request->purpose;
                $payment->save();

                self::saveCustomerName($payment, $exists);

                if ($request->purpose == 'ACCESS') {
                    return self::first($payment);
                } else {
                    return self::second($payment);
                }
            } else {
                $message = 'The package you have selected does not match the amount you paid. Please select the correct package!';
                return redirect()->back()
                    ->with('error', $message);
            }
        } else {
            $message = 'The Mpesa code you have entered is invalid. Please, try again.';
            return redirect()->back()
                ->with('error', $message);
        }
    }

    public function mapPackage($amount, $package)
    {
        if ($amount == $package->amount) {
            return true;
        } else {
            return false;
        }
    }

    public function codeExists($code)
    {
        $mpesaCode = \App\Mpesa::where('tran_id', $code)->first();

        if (!is_null($mpesaCode)) {
            if (is_null($mpesaCode->payment)) {
                return $mpesaCode;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function view($id)
    {
        $payment = Payment::where('merchant_req_id', $id)->first();

        if (is_null($payment)) {
            return redirect('/')->with('error', 'An error occured while processing your request.');
        } else {
            return view('mpesa.stkpending')
                ->with(compact('payment'));
        }
    }

    public function safaricom(Request $request)
    {
        $money = new Mpesa;
        if (isset($request['k']) && $request['k'] == config('app.mpesa_k')) {
            $payment = Payment::where('checkout_req_id', $request['Body']['stkCallback']['CheckoutRequestID'])->first();

            if ($request['Body']['stkCallback']['ResultCode'] == 0) {
                //success
                $payment->update([
                    'mpesa_code' => $request['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'],
                    'status' => 'COMPLETED',
                ]);
                $money->finishTransaction();
//                $holiday = self::checkHoliday();
//                $weekend = self::checkWeekend();
//                $packAllowed = self::checkPackage($payment->package);

//                if($weekend == true && $packAllowed == 'isDailyUnlimited')
//                {
//                    $duration = $payment->package->duration;
//                    self::sendMessage('You have been awarded the weekend offer!', $payment->phone_number);
//                }else{
//                    $duration = 0;
//                }
                $duration = 0;
                if ($payment->purpose == 'VOUCHER'){
                    self::offerVoucher($payment, $duration);
                }else{
                    self::grantAccess($payment, $duration);
                }

//                if($payment->package->amount >= 50)
//                {
//                    self::offerExtra($payment);
//                }

            } else {
                $payment->update([
                    'status' => 'FAILED',
                ]);
                $money->finishTransaction();
            }
        } else {
            $money->finishTransaction(false);
        }
    }

    public function paymentConfirmed(Request $request)
    {
        $payment = Payment::where('checkout_req_id', $request->reference)->first();

        if ($payment->status == 'COMPLETED') {
            $holiday = self::checkHoliday();
            $packAllowed = self::checkPackage($payment->package);
            if($holiday == true && $packAllowed == 'isDaily')
            {
                $duration = $payment->package->duration;
                self::sendMessage('You have been awarded the Holiday offer!', $payment->phone_number);
            }else{
                $duration = 0;
            }
            if ($payment->purpose == 'ACCESS') {
                return self::first($payment, $duration);
            } else {
                return self::second($payment, $duration);
            }
        } elseif ($payment->status == 'NEW') {
            return redirect()->route('payment', ['id' => $payment->merchant_req_id])
                ->with('info', 'Your Payment is still being processed.');
        } else {
            return redirect('/guest/s/default?id=' . $payment->client_mac . '&ap=' . $payment->ap_mac)
                ->with('error', 'There was a problem processing your request. Please, try again.');
        }
    }

    public function testC2b()
    {
        $mpesa = new Mpesa;
        $toBusiness = $mpesa->c2b('600364', 'CustomerPayBillOnline', '101',
            '254708374149', '0000');

        dd($toBusiness);
    }

    public function validateC2b(Request $request)
    {
        $money = new Mpesa;
        if (isset($request['k']) && $request['k'] == config('app.mpesa_k')) {
            $verify = self::verifyAmount($request['TransAmount']);

            if ($verify == true) {
                $money->finishTransaction();
            } else {
                $money->finishTransaction(false);
            }
        } else {
            $money->finishTransaction(false);
        }
    }

    public function confirmC2b(Request $request)
    {
        $money = new Mpesa;
        if (isset($request['k']) && $request['k'] == config('app.mpesa_k')) {
            $mpesa = new \App\Mpesa;
            $mpesa->trans_type = $request['TransactionType'];
            $mpesa->trans_time = $request['TransTime'];
            $mpesa->tran_id = $request['TransID'];
            $mpesa->bill_ref = $request['BillRefNumber'];
            $mpesa->trans_amount = $request['TransAmount'];
            $mpesa->third_party = $request['ThirdPartyTransID'];
            $mpesa->invoice_number = $request['InvoiceNumber'];
            $mpesa->account_bal = $request['OrgAccountBalance'];
            $mpesa->msisdn = $request['MSISDN'];
            $mpesa->first_name = $request['FirstName'];
            $mpesa->last_name = $request['MiddleName'] ?? $request['LastName'];
            $mpesa->save();

            $money->finishTransaction();
        } else {
            $money->finishTransaction(false);
        }
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

    public function first($payment, $duration)
    {
        $minutes = $payment->package->duration + $duration;
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
                $plex_auth = self::plex();
                return redirect('/')->with('status', 'You have successfully subscribed to the ' . $payment->package->name . ' that is ' . $payment->package->validity . '.');
//                return view('payments.authorized')->with('message', 'You have been granted access! Your plan expires in the next '.$hours.' hours.');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect('/')
                ->with('error', 'Transaction has already been processed!');
        }
    }

    public function grantAccess($payment, $duration)
    {
        $minutes = $payment->package->duration + $duration;
        $hours = $minutes / 60;
        $mb = $payment->package->m_bytes;
        $mac = $payment->client_mac;
        $up = $payment->package->up;
        $down = $payment->package->down;
        $ap = $payment->ap_mac;

//        Todo: enable this after landlords are added
//        self::payLandlord($payment);

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
//            $unifi->reconnect_sta($payment->client_mac);
            $auth = $unifi->authorize_guest($mac, $minutes, $ap, $mb, $up, $down);

            if ($auth == true) {
                $this->saveCustomer($payment);
            } else {
                self::saveCustomer($payment);
            }
        } else {
            self::saveCustomer($payment);
        }
    }

    public function second($payment, $duration)
    {
        if (is_null($payment->voucher) && is_null($payment->customer_id)) {
            $voucher = new Voucher;
            $voucher->voucher_code = $this->RandomString();
            $voucher->package_id = $payment->package_id;
            $voucher->payment_id = $payment->id;
            $voucher->user_id = '0';
            $voucher->duration = $payment->package->duration + $duration;
            $voucher->save();

            $this->sendMessage($voucher->voucher_code . ' is your voucher code for ' . $payment->package->name . '.', $payment->phone_number);

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

    public function newCustomer($payment)
    {
        $customerId = $payment->customer_id;
        $payments = Payment::where('customer_id', $customerId)->count();
        if ($payments == 1) {
            $voucher = self::doublePromo($payment);
            self::sendMessage($voucher->voucher_code. ' is your promotional Voucher code for ' . $voucher->package->name. '. Welcome to AIRFibers!', $payment->phone_number);
            return $payment;
        } else {
            return $payment;
        }
    }

    public function doublePromo($payment)
    {
        $voucher = new Voucher;
        $voucher->voucher_code = $this->RandomString();
        $voucher->package_id = $payment->package_id;
        $voucher->payment_id = $payment->id;
        $voucher->user_id = '0';
        $voucher->duration = $payment->package->duration;
        $voucher->save();

        return $voucher;
    }

    public function saveCustomer($pay)
    {
        if (Customer::where('phone_number', $pay->phone_number)->exists())
        {
            $customer = Customer::where('phone_number', $pay->phone_number)->first();
        }else{
            $customer = new Customer;
            $customer->phone_number = $pay->phone_number;
            $customer->name = '';
            $customer->save();
        }

        $pay->customer_id = $customer->id;
        $pay->granted = 1;
        $pay->save();

        if(MacAddress::where('mac', $pay->client_mac)->exists())
        {
            $dev = MacAddress::where('mac', $pay->client_mac)->first();
        }else{
            MacAddress::create([
                'mac' => $pay->client_mac,
                'ap_mac' => $pay->ap_mac,
                'customer_id' => $customer->id,
            ]);
        }
    }

    public function saveCustomerName($payment, $mpesa)
    {
        $customer = Customer::firstOrCreate([
            'phone_number' => $payment->phone_number,
        ], [
            'name' => $mpesa->first_name . ' ' . $mpesa->last_name,
        ]);

        $payment->customer_id = $customer->id;
        $payment->granted = true;
        $payment->save();

        $device = MacAddress::firstOrCreate([
            'mac' => $payment->client_mac,
        ],[
            'ap_mac' => $payment->ap_mac,
            'customer_id' => $customer->id,
        ]);
    }

    public function sendMessage($message, $phone)
    {
        $username = config('app.africastalking_username');
        $key = config('app.africastalking_key');
        //dd($username);

        $AT = new AfricasTalking($username, $key);

        $sms = $AT->sms();

        return $sms->send([
            'to' => $phone,
            'message' => $message,
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

    public function verifyAmount($amount)
    {
        $amounts = Package::all()->pluck('amount')->toArray();

        if (in_array($amount, $amounts)) {
            return true;
        } else {
            return false;
        }
    }

    public function formatPhone($phone)
    {
        $numbers = preg_split('//', $phone, -1, PREG_SPLIT_NO_EMPTY);

        if ($numbers[0] == '0') {
            $numbers[0] = '254';
            $number = implode('', $numbers);
            return $number;
        } elseif ($numbers[0] == '+') {
            array_shift($numbers);
            $number = implode('', $numbers);
            return $number;
        } elseif ($numbers[0] == '2') {
            $number = implode('', $numbers);
            return $number;
        } else {
            $number = implode('', $numbers);
            return $number;
        }
    }

    public function Timeout($payment)
    {
        if ($payment->created_at <= Carbon::now()->subMinutes(2)->toDateTimeString()){
            $payment->status = 'FAILED';
            $payment->save();
        }
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

    public function checkPackage($package)
    {
        if($package->quota_based == false && $package->duration <= 1440 ){
            return 'isDailyUnlimited';
        }elseif($package->duration <= 1440){
            return 'isDaily';
        }
    }

    public function sendUrl($payment)
    {
        self::sendMessage('Your payment has been processed successfully. Visit this address on on your browser and click "GET ACCESS". http://app.airfibers.com/pay/'.$payment->merchant_req_id,
            $payment->phone_number);
    }

    public function offerVoucher($payment, $duration){
        if (is_null($payment->voucher) && is_null($payment->customer_id) && $payment->granted == false) {
            $voucher = new Voucher;
            $voucher->voucher_code = $this->RandomString();
            $voucher->package_id = $payment->package_id;
            $voucher->payment_id = $payment->id;
            $voucher->user_id = '0';
            $voucher->duration = $payment->package->duration + $duration;
            $voucher->save();

            $this->sendMessage($voucher->voucher_code . ' is your voucher code for ' . $payment->package->name . '.', $payment->phone_number);

            self::saveCustomer($payment);
        }else{
            self::saveCustomer($payment);
        }
    }

    public function plex()
    {
        $plex = new Plex();
        $response = $plex->authenticate();
        dd($response);
        return $response;
    }

    public function payLandlord($payment)
    {
        $landlord = $payment->access_point->user;
        $landlord->float += ($payment->amount * 0.03);
        $landlord->save();
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

    public function query()
    {
        $mpesa = new Mpesa;
        $id = 'ws_CO_MER_30072019165520712';
        $BusinessShortCode = '886348';
        $timestamp = date("Ymdhis");
        $LipaNaMpesaPasskey = 'ebe6b04eec16967080c6880394717fb00bea095cd45449d77b57a0edb33b778c';
        $password = base64_encode($BusinessShortCode . $LipaNaMpesaPasskey . $timestamp);

        $query = $mpesa->STKPushQuery('live', $id, $BusinessShortCode, $password, $timestamp);

        echo $query;
        Log::info($query);
    }

    public function offerExtra($payment)
    {
        $package = Package::where('quota_based', false)->where('duration', 120)->first();
        $voucher = new Voucher;
        $voucher->voucher_code = $this->RandomString();
        $voucher->package_id = $package->id;
        $voucher->payment_id = $payment->id;
        $voucher->user_id = '0';
        $voucher->duration = $package->duration;
        $voucher->save();

        $this->sendMessage($voucher->voucher_code . ' is your promo voucher code for ' . $package->name . '.', $payment->phone_number);

    }

}
