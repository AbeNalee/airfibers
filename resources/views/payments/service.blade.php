<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AirFibers</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
            padding-top: auto;
            text-align: center;
        }
        .btn3d {
            transition:all .08s linear;
            position:relative;
            outline:medium none;
            -moz-outline-style:none;
            border:0px;
            margin-right:10px;
            margin-top:5px;
        }
        .btn3d:focus {
            outline:medium none;
            -moz-outline-style:none;
        }
        .btn3d:active {
            top:9px;
        }
        .btn-primary {
            box-shadow:0 0 0 1px #428bca inset, 0 0 0 2px rgba(255,255,255,0.15) inset, 0 8px 0 0 #357ebd, 0 8px 0 1px rgba(0,0,0,0.4), 0 8px 8px 1px rgba(0,0,0,0.5);
            background-color:#428bca;
        }
    </style>
</head>

<body id="container" class="container">
<div class="card-body">
        <?php
        if (isset($client) && isset($client->tx_bytes)) {
            $bytes = $client->tx_bytes + $client->rx_bytes;
            $mbytes = $bytes / 1048576;
            $url = '/store?id=' . $client->mac . '&ap=' . $client->ap_mac;

            $date = date('d/m/Y H:i:s', $client->end);
            if (property_exists($client, 'qos_overwrite') && $client->expired == false) {
                $balance = round($client->qos_usage_quota - $mbytes, 2) . ' mb';
                $message = 'Your bundle balance is ' . $balance . ' and expires on ' . $date;
            } elseif ($client->expired == true) {
                $message = 'You have no active bundle.';
            } else {
                $balance = 'unlimited';
                $message = 'Your bundle balance is ' . $balance . ' and expires on ' . $date;
            }
        }else{
            $url = '/store?id=' . $mac . '&ap=' . $ap;
            $message = 'You have no active bundle.';
        }

        ?>
        {{$message}}
        <div class="card-body">
            To purchase a bundle
            <a href="{{$url}}" class="btn btn-primary btn3d">
                    <span class="glyphicon glyphicon-cloud">
                        Click here
                    </span>
            </a>
        </div>
</div>
<div id="footer">
    @include('footer')
</div>
</body>
</html>
