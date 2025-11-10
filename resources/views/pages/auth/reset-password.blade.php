@extends('layouts.adminlte')
@section('title', 'Reset the Password')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="auth.reset-password-tokenized">
        <div class="callout callout-info">
            <p>Please set a new password for your account here.</p>
            <p>This page can only be accessed for one hour as stated in the email.</p>
        </div>
        <x-Adminlte.CardComponent id="theForm" :asForm="true" :withCaptcha="true" button="Change">
            <x-Form.InputForm name="new_password" type="password" text="New Password" :required="true" />
            <x-Form.InputForm name="new_password_confirmation" type="password" text="New Password Confirmation" :required="true" />
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
            routeAction = `{{ route('fe.auth.reset-password-tokenized', ['token' => $token]) }}`,

            // Init form action
            <x-Adminlte.FormComponent id="theForm" :isReset="false" :redirect="route('fe.auth.login')" />
        }
    </script>
@endpush