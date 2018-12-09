<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AirFibers</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
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
        &:not(:disabled) ~ label {
             cursor: pointer;
         }
        &:disabled ~ label {
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
        //margin: 1rem;
            text-align: center;
            box-shadow: 0px 3px 10px -2px hsla(150, 5%, 65%, 0.5);
            position: relative;
        }
        input[type="radio"]:checked + label {
            background: hsla(150, 75%, 50%, 1);
            color: hsla(215, 0%, 100%, 1);
            box-shadow: 0px 0px 20px hsla(150, 100%, 50%, 0.75);
        &::after {
             color: hsla(215, 5%, 25%, 1);
             font-family: FontAwesome;
             border: 2px solid hsla(150, 75%, 45%, 1);
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
            font-weight: 900;
        }
        span{
            font-weight: 800;
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

        a{
            font-weight: 600;
            font-style: italic;
            font-size: 15px;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body class="container">
<div class="text-lg-center flex-wrap col-md-10">
    <span>{{$message}}</span>
</div>

</body>
</html>
