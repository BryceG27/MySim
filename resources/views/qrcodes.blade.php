<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MySim - QRCodes</title>
    <style type="text/css">
        html, body, * {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    @foreach ($sims as $id => $sim)
        @if ($id >= $_GET['from'])
            @if ($id <= $_GET['to'] || $_GET['to'] == 0)
                {{-- @if ($id <= 5) --}}
                    <div style="page-break-after: always;">
                    	<div style="padding-top: 0.08in !important;">
                            <div style="text-align: center; ">
                                {!! QrCode::size(74)->generate(route('scanSim', ['iccid' => $sim->iccid, 'msisdn' => $sim->msisdn])) !!}
                                <div style="font-size: 0.5rem; line-height: 0rem; margin: 0; padding: 0;">{{$sim->msisdn}}</div>
                            </div>
                        </div>
                    </div>
                {{-- @endif --}}
            @endif
        @endif
    @endforeach
</body>
</html>
