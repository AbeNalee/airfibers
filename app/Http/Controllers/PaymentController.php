<?php

namespace App\Http\Controllers;

use App\Package;
use App\Payment;
use Pesapal;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //dd($request);
        $package = Package::find($request->package);
        //dd($package->id);
        $payment = new Payment;
        $payment->package_id = $package->id;
        $payment->phone_number = $request->phone;
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
        $iframe=Pesapal::makePayment($details);

        //dd($iframe);
        return view('payments.make', compact('iframe'));
    }

    public function paymentConfirmation(Request $request)
    {
        $trackingId = $request->tracking_id;
        $merchant_reference = $request->transactionRef;
        //dd($merchant_reference);
        $this->checkPaymentStatus($trackingId,$merchant_reference);
    }

    public function checkPaymentStatus($trackingId,$merchant_reference)
    {
        $status=Pesapal::getMerchantStatus($merchant_reference);
        //dd($elements);
        $payment = Payment::where('transaction_ref',$merchant_reference)->first();
        //dd($payment);
        $payment->update([
            'status' => $status,
            'tracking_id' => $trackingId,
            'payment_method' => 'Pesapal',
        ]);
        if ($status == 'PENDING')
        {
            return redirect('payments.home', compact('payment'));
        }
        elseif ($status == 'FAILED' || $status == 'INVALID')
        {
            return redirect('/')->with('status', 'Sorry, your payment is'.$status.'. Please, try again');
        }
        elseif($status == 'COMPLETE')
        {
            return redirect()->action('VoucherController@create', $payment);
        }
    }

    protected function GetClientMac(){
        $macAddr=false;
        $arp=`arp -n`;
        $lines=explode("\n", $arp);

        foreach($lines as $line){
            $cols=preg_split('/\s+/', trim($line));

            if ($cols[0]==$_SERVER['REMOTE_ADDR']){
                $macAddr=$cols[2];
            }
        }
        dd($macAddr);
        return $macAddr;
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
    public function paymentsuccess(Request $request)//just tells u payment has gone thru..but not confirmed
    {
        $trackingid = $request->input('tracking_id');
        $ref = $request->input('merchant_reference');

        $payment = Payment::where('transaction_ref',$ref)->first();
        $payment->update([
            'tracking_id' => $trackingid,
            'status' => 'PENDING',
        ]);
        //dd($payment);
        return view('payments.home', compact('payment'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}