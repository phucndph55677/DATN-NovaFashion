<!DOCTYPE html>
<html lang="en" class="theme-fs-md">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'NovaFashion | CRM Admin Dashboard')</title>

    @include('admin.partials.header')

</head>
<body class="">
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->

    
  <!-- Wrapper Start -->
  <div class="wrapper">

    @include('admin.partials.sidebar')

    @include('admin.partials.navbar')

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


  @include('admin.partials.footer')

  @include('admin.partials.script')

  @yield('scripts')
</body>
</html>
