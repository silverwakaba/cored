@extends('layouts.adminlte')
@section('title', 'Register')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="auth.register">
        <x-Adminlte.CardComponent id="theForm" :asForm="true" :withCaptcha="true" button="Register">
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

        // Init submit
        function initSubmit(){
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

                // Handle ajax
                $.ajax({
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    url: `{{ route('fe.auth.register') }}`,
                    success: function(response){
                        // Handle success
                        if(response.success){
                            // Swal message
                            Swal.fire({
                                toast: true,
                                icon: 'success',
                                position: 'top-right',
                                text: response.responseJSON.message,
                                timer: 1500,
                                showConfirmButton: false,
                            }).then(() => {
                                // Redirect
                                window.location.href = `{{ route('fe.auth.login') }}`;
                            });
                        }
                        else{
                            // API error
                            Swal.fire({
                                icon: 'error',
                                text: response.responseJSON.message || 'Something went wrong.',
                            }).then(() => {
                                // Reset form processing state
                                setProcessingState(false);
                            });
                        }
                    },
                    error: function(response){
                        // Refresh page if session/csrf_token expired
                        if([200, 419].includes(response.status)){
                            Swal.fire({
                                icon: 'warning',
                                text: response.responseJSON.message || 'We encountered a fatal error. Please try reloading the page.',
                                allowOutsideClick: () => {
                                    return false;
                                },
                            }).then(() => {
                                // Reload page
                                setTimeout(function(){
                                    window.location.reload();
                                }, 0);
                            });
                        }

                        // If error, form processing state is set as false
                        setProcessingState(false);

                        // Don't show message if status is come from submit error
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
        }

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
    </script>
@endpush