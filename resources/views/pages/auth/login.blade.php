@extends('layouts.adminlte')
@section('title', 'Login')
@section('content')
    <x-Adminlte.ContentWrapperComponent>
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
            // Handle form processing state
            function setProcessingState(processing){
                // Submit button
                let submit = $('#submitButton');
                let overlay = $('#overlay-card');

                // Set prop based on status
                if(processing){
                    submit.prop('disabled', true);

                    overlay.addClass('overlay').removeClass('d-none');
                }
                else{
                    submit.prop('disabled', false);

                    overlay.addClass('d-none').removeClass('overlay');
                }
            }

            // Handle form input while clearing previous action to avoid double submit
            $('#theForm').off('submit').on('submit', function(e){
                // Stop any possible unexpected default action
                e.preventDefault();

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // By default, when submitting the form, the state of form processing is set as true
                setProcessingState(true);

                // Populate form data into single variable
                let formData = new FormData(this);

                // Prepare url endpoint
                let endpoint = `{{ route('fe.auth.login') }}`;

                // Handle ajax
                $.ajax({
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    url: endpoint,
                    success: function(response){
                        // If success, form processing state is set as false
                        setProcessingState(false);

                        // Handle success
                        if(response.success){
                            // Swal message
                            Swal.fire({
                                toast: true,
                                icon: 'success',
                                position: 'top-right',
                                text: response.message,
                                timer: 3000,
                                showConfirmButton: false,
                            }).then(() => {
                                // Redirect
                                window.location.href = "{{ route('fe.apps.page.index') }}";
                            });
                        }
                        else{
                            // unknown error
                        }
                    },
                    error: function(response){
                        // If error, form processing state is set as false
                        setProcessingState(false);

                        // Don't show message if status 422
                        if(response.status != 422){
                            // Swal message
                            Swal.fire({
                                toast: true,
                                icon: 'error',
                                position: 'top-right',
                                text: response.responseJSON.message,
                                timer: 3000,
                                showConfirmButton: false,
                            });
                        }

                        // Refresh page if status is 419
                        if(response.status == 419){
                            setTimeout(function(){
                                window.location.reload();
                            }, 1500);
                        }

                        // Handle error message
                        let errors = response.responseJSON.errors;
                            
                        $.each(errors, function(key, value){
                            let input = $('[name="' + key + '"]');
                            let errorElement = $('#' + key + '-error');
                            
                            input.addClass('is-invalid');
                            errorElement.text(value[0]);
                        });
                    }
                });
            });
        });
    </script>
@endpush