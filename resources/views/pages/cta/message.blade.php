@extends('layouts.adminlte')
@section('title', 'Send us a Message')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="cta.message">
        <div class="callout callout-info">
            <p>You can send us an inquiry message via the form below.</p>
        </div>
        <x-Adminlte.CardComponent id="theForm" :asForm="true" :withCaptcha="true" button="Send">
            <x-Form.InputForm name="name" type="text" text="Name" :required="true" />
            <x-Form.InputForm name="email" type="email" text="Email" :required="true" />
            <x-Form.InputForm name="subject" type="text" text="Subject" :required="true" />
            <x-Form.InputForm name="message" type="text" text="Message" :required="true" :asTextarea="true" />
            <x-Form.InputForm name="attachment[]" type="file" text="Attachment" :required="false" :asFile="true" :multiple="true" />
            <x-Form.CheckboxForm name="agreement" :value="true" :required="true">I confirm that the message I am sending contains accurate information and is in good faith.</x-Form.CheckboxForm>
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
            routeAction = `{{ route('fe.cta.message') }}`;

            // Init form action
            <x-Adminlte.FormComponent id="theForm" :isReset="false" />
        }
    </script>
@endpush