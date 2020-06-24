@extends('layouts.layout')

@section('content')
    <div class="col-md-9 col-sm-12 content-holder">
        <!-- CONTENT -->
        <div id="menu-container">
            <div class="logo-holder logo-top-margin">
                <a href="#" class="site-brand"><img src="{{asset('/images/logo.png')}}" alt=""></a>
            </div>
            <div id="menu-1" class="homepage home-section text-center">
                <?php
                if (isset($client) && isset($client->tx_bytes)) {
                    $bytes = $client->tx_bytes + $client->rx_bytes;
                    $mbytes = $bytes / 1048576;
                    $ap_mac = $client->ap_mac ?? $ap;
                    $url = '/store?id=' . $client->mac . '&ap=' . $ap_mac . '&path=';

                    $date = date('d/m/Y H:i', $client->end);
                    if (property_exists($client, 'qos_overwrite') && $client->expired == false) {
                        $balance = round($client->qos_usage_quota - $mbytes, 2) . ' MB';
                        $button = '<span class="btn btn-default btn-light" onClick="disabled()">Apply Voucher</span>';
                    } elseif ($client->expired == true) {
                        $balance = '0.00';
                        $date = '- -';
                        $button = '<input type="submit" class="btn btn-primary" value="Apply Voucher">';
                    } else {
                        $balance = 'Unlimited';
                        $button = '<span class="btn btn-default btn-light" onClick="disabled()">Apply Voucher</span>';
                    }
                } else {
                    $url = '/store?id=' . $mac . '&ap='.$ap.'&path=';
                    $balance = '00.00';
                    $date = '- -';
                    $button = '<input type="submit" class="btn btn-primary" value="Apply Voucher">';
                }

                ?>
                <div class="welcome-text">
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
                    {{--@if($errors->any())--}}
                    {{--<div class="alert alert-danger">{{$errors->first()}}</div>--}}
                    {{--@endif--}}
                    <!--h2>Welcome to <strong>AIRFibers</strong></h2-->
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th scope="row">Bundle Balance</th>
                                    <td class="blue">{{$balance}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Bundle Expiry</th>
                                    <td class="red">{{$date}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--p>Browse with <span class="orange"><strong>incredible speeds</strong></span> and enjoy
                        <strong>latest movies</strong> and your favourite</span>
                        <span class="green"><strong>TV shows</strong></span> All-In-One at affordable rates...</p>
                    <p><span class="orange">Grab your suitable bundle and join the fun!</span></p-->

                    <div class="visible-xs visible-sm responsive-menu show-menu">
                        <ul>
                            <li>
                                <a title="If you want to make a quick payment, select this option" class="show-2 aboutbutton" href="{{$url}}&path=online">
                                    <i class="fa fa-bar-chart" ></i>
                                    Purchase Bundles (M-pesa)
                                </a>
                            </li>
                            <!--li>
                                <a class="show-2 homebutton" title="If you already have a pending payment, select this option" href="{{$url}}&path=offline">
                                    <i class="fa fa-bar-chart"></i>
                                    Get Bundles (Already paid to Till Number)
                                </a>
                            </li-->
                            <li>
                                <a class="show-3 projectbutton" href="http://media.airfibers.com"><i
                                            class="fa fa-film"></i>Movies and TV shows</a>
                            </li>
                        </ul>
                    </div>

                    <form action="/voucher" method="post" class="subscribe-form">
                        @csrf
                        <input type="hidden" name="mac" value="{{$mac}}">
                        <input type="hidden" name="ap" value="{{$ap}}">
                        <div class="row">
                            <fieldset class="col-md-offset-2 col-md-6">
                                <input name="voucher" type="text" class="email text-white" id="subscribe-email"
                                       placeholder="Got a Voucher code? insert here..">
                            </fieldset>
                            <fieldset class="col-md-4 button-holder">
                                {!! $button !!}
                            </fieldset>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <div class="col-md-3 hidden-sm">
        <nav id="nav" class="main-navigation hidden-xs hidden-sm">
            <ul>
                <li>
                    <a title="If you want to make a quick payment, select this option" class="show-2 aboutbutton" href="{{$url}}online">
                        <i class="fa fa-bar-chart" ></i>
                        Purchase Bundles (M-pesa)
                    </a>
                </li>
                <!--li>
                    <a class="show-2 homebutton" title="If you already have a pending payment, select this option" href="{{$url}}offline">
                        <i class="fa fa-bar-chart"></i>
                        Get Bundles (Already paid to Till Number)
                    </a>
                </li-->
                <li>
                    <a class="show-3 contactbutton" href="http://media.airfibers.com"><i
                                class="fa fa-film"></i>Movies and TV shows</a>
                </li>
            </ul>
        </nav>

    </div>

@endsection