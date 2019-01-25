<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head')
<body class="container" id="container">
<div class="card-body">

    {!! $iframe !!}
</div>
<div class="card-footer">

    <footer id="footer">
        @include('footer')
    </footer>

</div>
</body>
</html>
