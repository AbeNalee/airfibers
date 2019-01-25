<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head')
<body id="container" class="container">
<div class="card-body">
    @foreach($clients as $client)
        <?php
        $bytes = $client->tx_bytes + $client->rx_bytes;
        $mbytes = $bytes/1048576;
        $url = '/guest/s/default?id='.$client->mac.'&ap='.$client->ap_mac;
//        dd($url);
        $date = date('d/m/Y H:i:s', $client->end);
        if (property_exists($client, 'qos_overwrite') && $client->expired == false)
        {
            $balance = round($client->qos_usage_quota - $mbytes, 2). ' mb';
            $message = 'Your bundle balance is '.$balance.' and expires on '.$date;
        }
        elseif ($client->expired == true)
        {
            $message = 'You have no active bundle.';
        }
        else
        {
            $balance = 'unlimited';
            $message = 'Your bundle balance is '.$balance.' and expires on '.$date;
        }
        ?>
        {{$message}}
            <div class="card-body">
                To purchase another bundle
                <a href="{{$url}}" class="btn btn-primary btn3d">
                    <span class="glyphicon glyphicon-cloud">
                        Click here
                    </span>
                </a>
            </div>
    @endforeach
</div>
<div id="footer">
    @include('footer')
</div>
</body>
</html>
