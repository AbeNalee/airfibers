@extends('layouts.layout')
@section('content')
    @if(session('info'))
        <div class="alert alert-info">
            {{session('info')}}
        </div>
    @endif
    <div class="text-center">
        <span style="font-size: 18px; font-weight: bold">
            Lipa na M-Pesa has been initiated on your phone.
            <br>
            Please enter your M-Pesa Pin when prompted and click OK.
            <br>
            Once done, click the "GET ACCESS" button below.
        </span>
        <br><br>
        <form method="post" action="/donepayment" class="card-body">
            @csrf
            <input hidden name="reference" type="radio" value="{{$payment->checkout_req_id}}" checked/>
            <fieldset>
                <input id="validatebtn" name="validated" type="submit" class="button btn-lg btn-primary"
                       value="GET ACCESS"/>
            </fieldset>
        </form>
    </div>



@endsection