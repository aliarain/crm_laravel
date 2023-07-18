<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | {{ _trans('error.Validation Message Generate') }}</title>
    <link rel="stylesheet" href="{{ asset('public/frontend/css/') }}/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="text-success">Validation Message Generate</h2>
        <form action="{{ route('message_generate') }}" method="post">
            @csrf
            <input type="text" name="field" value="{{ $field ?? '' }}" placeholder="Field">
            <input type="text" name="rules" value="{{ $rules ?? '' }}" placeholder="rules">
            <input type="submit" class="btn btn-success"  value="Submit">
        </form>

        @if(isset($arr) and count($arr))
        <ul>

            @foreach($arr as $key => $value)
               <li>'{{ $key }}' => '{{$value}}',</li>
            @endforeach
        </ul>
        @endif
    </div>
</body>
</html>
