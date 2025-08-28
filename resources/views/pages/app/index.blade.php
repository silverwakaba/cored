@extends('layouts.adminlte')
@section('title', 'App')
@section('content')
    <x-Adminlte.ContentWrapperComponent>
        <x-Adminlte.CardComponent id="theForm" :asForm="false" :upsert="false">
            App
        </x-Adminlte.CardComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Init jquery
        $(document).ready(function(){
            // Handle overlay class for form processing state
            function setProcessingState(processing){
                const submit = $('#buttonSubmit');
                const overlay = $('#overlay');

                if(processing){
                    submit.prop('disabled', true);

                    overlay.addClass('overlay').removeClass('d-none');
                }
                else{
                    submit.prop('disabled', false);

                    overlay.addClass('d-none').removeClass('overlay');
                }
            }

            // Init upsert (Update or Insert)
            $('body').on('click', '#btn-upsert', function(){
                // Open modal
                $('#theModalModal').modal('show');

                // Reset the form
                $('#buttonReset').trigger('click');

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Get data id
                let dataID = $(this).data('id');

                // Handle insert
                if(!dataID){
                    console.log('Insert');
                }

                // Handle update
                else{
                    console.log('Update');
                }
            });
        });
    </script>
@endpush