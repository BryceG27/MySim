<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recover you Password</title>
    <!-- Poppins 400 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- my CSS -->
    <style>
        body {
            background-color: #f9f9f9;
            color: #333333;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
        }
        .bg-color {
            background-color: #e0e0e0;
            border-radius: 10px;
            padding: 10px 30px;
        }
        .my-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            max-width: 100%;
            padding: 0 5%;
        }
        .column {
            display: flex;
            flex-direction: column;
        }
        .margin {
            margin-bottom: 2rem;
        }
        .padding {
            padding-bottom: 1rem;
        }
        div > img {
            margin-bottom: 1rem;
        }
        /* CSS custom button - INIZIO */
        .button-26 {
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            cursor: pointer;
            color: #f9f9f9;
            background-color: #1652F0;
            border: 1px solid #1652F0;
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 80ms ease-in-out;
            -webkit-user-select: none;
        }
        .button-26:disabled {
            opacity: .5;
        }
        .button-26:focus {
            outline: 0;
        }
        .button-26:hover {
            background-color: #0A46E4;
            border-color: #0A46E4;
        }
        .button-26:active {
            background-color: #0039D7;
            border-color: #0039D7;
        }
        /* CSS custom button - FINE */
    </style>
</head>
<body>
    <div class="my-container">
        <div class="column">
            <!-- Modificare Immagine con LOGO azienda -->
            <div>
                <img src="{{ Storage::url('img/mysim.png') }}" alt="{{ $company ?? '' }}" width="150">
            </div>
            {{-- Come gestisco il Locale?? --}}
            <div class="bg-color">
                {{-- {{ Auth::user()->all() }} --}}
                <h1>{{ __('messages.email.verification_header') }} {{ $data['Name'] }}!</h1>
                <p class="padding">{{ __('messages.email.verification_instruction') }}</p>
                <a href="{{ $verificationUrl }}" class="button-26">{{ __('messages.email.verification_button') }}</a>
                <p>{{ __('messages.email.verification_secure_message') }}</p>
                <p>{{ __('messages.email.verification_thanks') }}<br /><strong>Atik SRL</strong></p>
                <hr>
                <p>{{ __('messages.email.verification_button_problem') }}</p>
                <p>{{ $verificationUrl }}</p>
                <br />
            </div>
        </div>
    </div>
</body>
</html>
