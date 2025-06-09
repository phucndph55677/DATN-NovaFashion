<!DOCTYPE html>
<html lang="en" class="theme-fs-md">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="N4bfmjN5LEW5HT2ziKoPvKUNnsQCty6ldtdDitFK">

    <title>@yield('title', 'NovaFashion | CRM Admin Dashboard')</title>

    @include('partials.header')

</head>
<body class="">
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->

    
  <!-- Wrapper Start -->
  <div class="wrapper">

    @include('partials.sidebar')

    @include('partials.navbar')

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
  <!-- Wrapper End-->


  @include('partials.footer')

  @include('partials.script')

  @yield('scripts')
</body>
</html>
