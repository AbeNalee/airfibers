@extends('layouts.layout')
@section('content')
    <span class="text-lg-center">Your Payment of ksh.{{$payment->package->amount}}
        for {{$payment->package->name }} {{$payment->package->validity}}
        has been successful! You can use the data bundle on this device or choose to get a voucher code to use later or on another device.
        <br>

    </span>
    <br>
    <!-- Todo: Change if statement here --done -->
    <?php
    if (isset($client) && isset($client->expired)){
        if($client->expired == false){
            $button = '<span class="btn btn-default btn-light" onClick="disabled()">Grant me access</span>';
        }else{
            $button = '<button type="submit" class="btn btn-lg btn-primary">Grant me Access</button>';
        }
    }else{
        $button = '<button type="submit" class="btn btn-lg btn-primary">Grant me Access</button>';
    }
    ?>
    <p class="text-lg-center text-center">Connect! Explore! Experience!</p>
    <form class="flex-center btn-toolbar text-center" method="post" action="/first">
        @csrf
        <input type="hidden" name="payment" value="{{$payment->id}}">
        {!! $button !!}
        <button type="submit" class="btn btn-lg btn-primary" formaction="/second">I just want a Voucher code</button>
    </form>
@endsection