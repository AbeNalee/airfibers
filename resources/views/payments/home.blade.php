@extends('layouts.layout')
@section('content')
    <span class="text-lg-center">Your Payment is being processed.<strong class="orange">Please wait for the Pesapal confirmation message then click the button below</strong></span>
    <form method="post" action="/donepayment" class="card-body">
        @csrf
        <input hidden name="transactionRef" type="radio" value="{{$payment->transaction_ref}}" checked/>
        <input hidden name="tracking_id" type="radio" value="{{$payment->tracking_id}}" checked/>
        <fieldset>
            <input id="validatebtn" name="validated" type="submit" class="button btn-lg btn-primary"
                   value="Click here to complete"/>
        </fieldset>


    </form>
@endsection