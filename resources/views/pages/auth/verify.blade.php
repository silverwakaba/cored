@extends('layouts.adminlte')
@section('title', 'Verify Account')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="auth.verify-account">
        <div class="callout callout-info">
            <p>Here you can request a fresh token to verify your account.</p>
            <p>There is a cooldown of approximately one hour for each request.</p>
        </div>
        <x-Adminlte.CardComponent id="theForm" :asForm="true" :withCaptcha="true" button="Check">
            <x-Form.InputForm name="email" type="email" text="Email" :required="true" />
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
            routeAction = `{{ route('fe.auth.verify-account') }}`;

            // Init form action
            <x-Adminlte.FormComponent id="theForm" :isReset="false" :redirect="route('fe.auth.login')" />
        }
    </script>
@endpush