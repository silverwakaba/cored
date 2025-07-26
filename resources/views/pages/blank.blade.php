@extends('layouts.adminlte')
@section('title', 'Blank')
@section('content')
    <x-Adminlte.ContentWrapperComponent title="Aru">
        <x-Adminlte.CardComponent id="theForm" :asForm="false" :upsert="true" title="ABC">
            <!--  -->
        </x-Adminlte.CardComponent>

        <!-- Modal Disini -->
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" title="DEF">
            <!-- Input -->
            <x-Form.InputForm name="theInput" type="text" text="The Input" :hidden="false" :required="true" />

            <!-- Select -->
            <x-Form.SelectForm name="theSelectSingle" text="The Select Single" :hidden="false" :required="true" :multiple="false" />
            <x-Form.SelectForm name="theSelectMultiple" text="The Select Multiple" :hidden="false" :required="true" :multiple="true" />
        </x-Adminlte.ModalComponent>
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