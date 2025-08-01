<!DOCTYPE html>
<html lang="en" class="theme-fs-md">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="N4bfmjN5LEW5HT2ziKoPvKUNnsQCty6ldtdDitFK">

    <title>@yield('title', 'NovaFashion')</title>

    @include('client.partials.header')
</head>
    <body class="">

        @include('client.partials.navbar')

        <div class="content-page ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        </div>

        @include('client.partials.footer')

        {{-- Modal / Overlay toàn trang --}}
        <div id="overlay"></div>
        <div class="modal_loading"><!-- Place at bottom of page --></div>

        @include('client.partials.script')

        @yield('scripts')
    </body>
</html>
