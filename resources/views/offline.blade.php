<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AIRFibers SelfCare</title>
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}"/>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}"/>
    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style lang="scss">
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        body {
            padding: 1rem;
            color: hsla(215, 5%, 50%, 1);
        }

        h1 {
            color: hsla(215, 5%, 10%, 1);
            margin-bottom: 2rem;
        }

        section {
            display: flex;
            flex-flow: row wrap;
        }

        section > div {
            flex: 1;
            padding: 0.5rem;
        }

        input[type="radio"] {
            display: none;

        &
        :not(:disabled) ~ label {
            cursor: pointer;
        }

        &
        :disabled ~ label {
            color: hsla(150, 5%, 75%, 1);
            border-color: hsla(150, 5%, 75%, 1);
            box-shadow: none;
            cursor: not-allowed;
        }

        }
        label {
            height: 100%;
            display: block;
            background: white;
            border: 2px solid hsla(150, 75%, 50%, 1);
            border-radius: 20px;
            padding: 1rem;
            margin-bottom: 1rem;
        / / margin: 1 rem;
            text-align: center;
            box-shadow: 0px 3px 10px -2px hsla(150, 5%, 65%, 0.5);
            position: relative;
        }

        input[type="radio"]:checked + label {
            background: #358f6e;
            color: hsla(215, 0%, 100%, 1);
            box-shadow: 0px 0px 20px hsla(150, 100%, 50%, 0.75);

        &
        ::after {
            color: hsla(215, 5%, 25%, 1);
            font-family: FontAwesome;
            border: #03cbfc;
            content: "\f00c";
            font-size: 24px;
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            height: 50px;
            width: 50px;
            line-height: 50px;
            text-align: center;
            border-radius: 50%;
            background: white;
            box-shadow: 0px 2px 5px -2px hsla(0, 0%, 0%, 0.25);
        }

        }
        input[type="radio"]#5:checked + label {
            background: red;
            border-color: red;
        }

        p {
            font-weight: 400;
        }

        span {
            /*font-weight: 300;*/
            /*color: hsla(215, 5%, 10%, 1);*/
            font-size: 20px;
        }

        @media only screen and (max-width: 700px) {
            section {
                flex-direction: column;
            }
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        a {
            font-weight: 600;
            font-style: italic;
            font-size: 15px;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .wrap-collabsible {
            margin-bottom: 1.2rem 0;
        }

        input[type='checkbox'] {
            display: none;
            font-family: FontAwesome;
        }

        .lbl-toggle {
            display: block;

            font-weight: bold;
            font-family: monospace;
            font-size: 1.2rem;
            text-transform: uppercase;
            text-align: center;

            padding: 1rem;

            color: #163a2d;
            background: #45baa5;

            cursor: pointer;

            border-radius: 7px;
            transition: all 0.25s ease-out;
        }

        .lbl-toggle:hover {
            color: #06100c;
            background: #358f6e;
        }

        .lbl-toggle::before {
            content: ' ';
            display: inline-block;

            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            border-left: 5px solid currentColor;
            vertical-align: middle;
            margin-right: .7rem;
            transform: translateY(-2px);

            transition: transform .2s ease-out;
        }

        .toggle:checked + .lbl-toggle::before {
            transform: rotate(90deg) translateX(-3px);
        }

        .collapsible-content {
            max-height: 0px;
            overflow: hidden;
            transition: max-height .25s ease-in-out;
        }

        .toggle:checked + .lbl-toggle + .collapsible-content {
            max-height: 3500px;
        }

        .toggle:checked + .lbl-toggle {
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
        }

        .collapsible-content .content-inner {
            background: #ffffff;
            border-bottom-left-radius: 7px;
            border-bottom-right-radius: 7px;
            padding: .5rem 1rem;
        }

        .leading-input {
            width: 50px;
            border: 1px solid;
            height: 30px;
            /*position: absolute;*/
            background: #eeeeee;
            padding-right: 6px;
            padding-left: 6px;
            padding-top: 4px;
            padding-bottom: 4px;
            font-weight: bold;
            display: inline;
            vertical-align: middle;
            cursor: not-allowed;
            color: #555555;
        }

        .follower-input {
            margin: 0 auto;
            border: 2px solid;
            height: 30px;
            vertical-align: middle;
            background: #ffffff;
        }

        .phone_number {
            display: table-row;
            position: relative;
        }
        .site-bg {
            position: fixed;
            height: 100%;
            width: 100%;
            z-index: 0;
            background-image: url(../images/bg.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .site-bg-overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            background: rgba(120, 80, 120, 0.2);
        }
    </style>
    <script type="text/javascript">
        function disabled() {
            alert('This device already has an active bundle! You can choose to get a voucher for later use.')
        }
    </script>
</head>
<body class="container">
<div class="card-body mb-5">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <h1 class="flex-center">Select a Package Below</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif
    <form method="post" action="/payment-offline" class="form card-body">
        @csrf
        <div class="form-row">
            <input type="hidden" name="mac" value="{{$mac}}">
            <input type="hidden" name="ap" value="{{$ap}}">

            <div class="card content wrap-collabsible">
                <input id="collapsible" class="toggle" type="checkbox">
                <label for="collapsible" class="lbl-toggle">Unlimited Packages</label>
                <div class="collapsible-content">
                    <div class="content-inner">
                        <section>
                            @foreach($unlimited_packs as $pack)
                                <div>
                                    <input type="radio" id="{{$pack->id}}" name="package" value="{{$pack->id}}">
                                    <label for="{{$pack->id}}">
                                        <h2>{{$pack->name}}</h2>
                                        <p>{{$pack->description}}</p>
                                        <span>Ksh.{{$pack->amount}}</span>
                                    </label>
                                </div>
                            @endforeach
                        </section>
                    </div>
                </div>
            </div>
            <div class="card content wrap-collabsible">
                <input id="collapsible2" class="toggle" type="checkbox">
                <label for="collapsible2" class="lbl-toggle">Daily Bundles</label>
                <div class="collapsible-content">
                    <div class="content-inner">
                        <section>
                            @foreach($daily_packs as $pack)
                                <div>
                                    <input type="radio" id="{{$pack->id}}" name="package" value="{{$pack->id}}">
                                    <label for="{{$pack->id}}">
                                        <h2>{{$pack->name}}</h2>
                                        <p>{{$pack->description}}</p>
                                        <span>Ksh.{{$pack->amount}}</span>
                                    </label>
                                </div>
                            @endforeach
                        </section>
                    </div>
                </div>
            </div>
            <div class="card content wrap-collabsible">
                <input id="collapsible3" class="toggle" type="checkbox">
                <label for="collapsible3" class="lbl-toggle">Weekly Bundles</label>
                <div class="collapsible-content">
                    <div class="content-inner">
                        <section>
                            @foreach($weekly_packs as $pack)
                                <div>
                                    <input type="radio" id="{{$pack->id}}" name="package" value="{{$pack->id}}">
                                    <label for="{{$pack->id}}">
                                        <h2>{{$pack->name}}</h2>
                                        <p>{{$pack->description}}</p>
                                        <span>Ksh.{{$pack->amount}}</span>
                                    </label>
                                </div>
                            @endforeach
                        </section>
                    </div>
                </div>
            </div>
            <div class="card content wrap-collabsible">
                <input id="collapsible4" class="toggle" type="checkbox">
                <label for="collapsible4" class="lbl-toggle">Monthly Bundles</label>
                <div class="collapsible-content">
                    <div class="content-inner">
                        <section>
                            @foreach($monthly_packs as $pack)
                                <div>
                                    <input type="radio" id="{{$pack->id}}" name="package" value="{{$pack->id}}">
                                    <label for="{{$pack->id}}">
                                        <h2>{{$pack->name}}</h2>
                                        <p>{{$pack->description}}</p>
                                        <span>Ksh.{{$pack->amount}}</span>
                                    </label>
                                </div>
                            @endforeach
                        </section>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <?php
        if (isset($client) && isset($client->expired)){
            if($client->expired == false){
                $button = '<input type="radio" name="purpose" id="access" value="ACCESS" disabled>
                                    <label for="access" class="btn btn-default" onclick="disabled()">
                                        <span>Connect this device</span>
                                    </label>';
            }else{
                $button = '<input type="radio" id="access" name="purpose" value="ACCESS">
                                    <label for="access" class="btn btn-default">
                                        <span>Connect this device</span>
                                    </label>';
            }
        }else{
            $button = '<button type="submit" class="btn btn-lg btn-primary">Grant me Access</button>';
        }
        ?>
        <div class="row flex-center hidden-sm mx-md-n5 mb-3">
            {!! $button !!}
            <span class="col-md-1 flex-center">  Or  </span>
            <input type="radio" id="voucher" name="purpose" value="VOUCHER">
            <label for="voucher" class="btn btn-default">
                <span>Get voucher for later use</span>
            </label>
        </div>
        <div class="form-group col-md-12 row mb-3 col-sm-7">
            <span>Enter Mpesa Confirmation Code:</span>
            <div class="form-row">
                <input type="text" name="code" id="code" class="form-control" required/>
            </div>
        </div>
        <br>
        <div class="form-group form-row mb-3 flex-row">
            <button type="submit" class="btn btn-lg btn-primary">Make Purchase</button>
        </div>
    </form>
</div>
<div class="card-footer col-md-12 flex-center mt-5">
    @include('footer')
</div>
</body>

</html>
