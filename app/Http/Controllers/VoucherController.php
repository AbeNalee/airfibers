<?php

namespace App\Http\Controllers;

use App\Package;
use App\Voucher;
use Illuminate\Http\Request;

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
    public function create($payment)
    {
        dd($payment);
        $voucher = new Voucher;
        $voucher->voucher_code = $this->RandomString();
        $voucher->package_id = $payment->package_id;
        $voucher->payment_id = $payment->id;
        $voucher->duration = $payment->package->duration;
        $voucher->save();

        return redirect('/voucher')->with('status', 'Transaction has been completed succcessfully. You will receive a voucher code via sms shortly');
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
