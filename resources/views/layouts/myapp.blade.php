<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <title>{{ config('app.name', 'Retailbaseline') }}</title>

    <head>
        <meta charset="UTF-8">
        <!-- <title>{{ env('APP_NAME') }}</title> -->
        <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'>
        <meta name="Description" content="Retailbaseline">
        <meta name="csrf-token" content="{{ csrf_token()}}">
        <meta name="theme-color" content="#fff"/>
        @yield('form_css')
        <link rel="stylesheet" href="{{ asset('css/app.css?v=1.1') }}">
        <link rel="stylesheet" href="{{ asset('css/carousel.min.css') }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <!-- Select 2 -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <script src='https://kit.fontawesome.com/a076d05399.js'></script>
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        
        @yield('css')
        @yield('select2')
        @stack('styles')
        <style>
        div.users {
            display:none;
        }
        </style>
    </head>

    <body>
        <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        @include('layouts.header.index')
            <div class='app-main'>
                {{-- @include('layouts.sidebar.index') --}}
                @role('Super Admin')
                    @include('layouts.sidebar.superadmin_menu')
                @endrole
                @role('Admin')
                    @include('layouts.sidebar.admin_menu')
                @endrole
                @role('Regional Admin')
                    @include('layouts.sidebar.regional_admin_menu')
                @endrole
                @role('Supervisor')
                    @include('layouts.sidebar.supervisor_menu')
                @endrole
                @role('Field Staff')
                    @include('layouts.sidebar.field_staff_menu')
                @endrole
                <div class='app-main__outer'>
                    <div class='app-main__inner'>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        <script src="{{asset('js/app.js?v=1.1')}}" type="text/javascript"></script>
        @stack('scripts')
        
        <script>
            $(".autoremove").delay(6000).slideUp(200, function() {
                $(this).autoremove('close');
            });

            var downloadReport = (id, title) => {
                reactDownloadReport(id, title)
            }
            var deleteReport = (id) => {
                reactDeleteReport(id)
            }
            var deleteEntityData = (id) => {
                reactDeleteEntityData(id)
            }
            var deleteReportData = (id) => {
                reactDeleteReportData(id)
            }
            var staffAttendance = (image_name) => {
                reactStaffAttendance(image_name)
            }
        </script>
    </body>
</html>
