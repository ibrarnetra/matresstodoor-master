<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Zone</title>
    </head>

    <body>
        {!! implode('', $errors->all('<span>:message</span>')) !!}
        <form method="post" action="{{route('zones.importExcel')}}" enctype="multipart/form-data">
            @csrf
            <input class="form-control @error('file') is-invalid @enderror" type="file" name="file" accept=".xlsx, .xls, .csv" required>
            @error('slug')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <button type="submit">Submit</button>
            <a href="{{route('countries.import')}}">Countries Import</a>
        </form>
        @if (session('success'))
        <p>{{ session('success') }}</p>
        @endif
    </body>

</html>