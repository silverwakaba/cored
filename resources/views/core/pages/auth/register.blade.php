@extends('layouts.adminlte')
@section('title', 'Register')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="auth.register">
        <x-Adminlte.CardComponent id="theForm" :asForm="true" :withCaptcha="false" button="Register">
            <x-Form.InputForm name="name" type="text" text="Name" :required="true" />
            <x-Form.InputForm name="email" type="email" text="Email" :required="true" />
            <x-Form.InputForm name="password" type="password" text="Password" :required="true" />
            <x-Form.InputForm name="password_confirmation" type="password" text="Password Confirmation" :required="true" />
            <x-Form.CheckboxForm name="agreement" :value="true" :required="true">I agree</x-Form.CheckboxForm>
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
            routeAction = `{{ route('fe.auth.register') }}`;

            // Init form action
            <x-Adminlte.FormComponent id="theForm" :isReset="false" :redirect="route('fe.auth.login')" />
        }
    </script>
@endpush