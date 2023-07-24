{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MySim - QRCodes</title>
    <style type="text/css"> @page{ size: 400mm 400mm; margin: 0;}</style>
</head>
<body>

    <div style="page-break-after: always; text-align: center">
        {!! QrCode::size(153)->generate(route('scanSim', ['iccid' => $icc, 'msisdn' => $msisdn])) !!}
        <div>{{$msisdn}}</div>

    </div>
    
</body>
</html> --}}

<x-layout page="QRCode">
    
    <div class="text-center">
        {!! QrCode::size(153)->generate(route('scanSim', ['iccid' => $icc, 'msisdn' => $msisdn])) !!}
        <div>{{$msisdn}}</div>

        <a href="{{ route('scanSim', ['iccid' => $icc, 'msisdn' => $msisdn]) }}" class="link-primary">
            {{ route('scanSim', ['iccid' => $icc, 'msisdn' => $msisdn]) }}
        </a>
    </div>

</x-layout>
