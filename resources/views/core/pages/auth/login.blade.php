@extends('layouts.adminlte')
@section('title', 'Login')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="auth.login">
        <x-Adminlte.CardComponent id="theForm" :asForm="true" :withCaptcha="false" button="Login">
            <x-Form.InputForm name="email" type="email" text="Email" :required="true" />
            <x-Form.InputForm name="password" type="password" text="Password" :required="true" />
            <x-Form.CheckboxForm name="remember" :value="true">Remember Me</x-Form.CheckboxForm>
        </x-Adminlte.CardComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Init jquery
        $(document).ready(function(){
            // Load init function
            initSubmit();
        });

        // Handle overlay class for form processing state
        <x-Adminlte.ProcessingStateComponent type="card" />

        // Init submit
        function initSubmit(){
            // Set route action
            routeAction = `{{ route('fe.auth.login') }}`;

            // Init form action
            <x-Adminlte.FormComponent id="theForm" :isReset="false" :redirect="route('fe.apps.index')" />
        }
    </script>
@endpush