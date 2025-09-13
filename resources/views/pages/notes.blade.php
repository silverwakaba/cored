@extends('layouts.adminlte')
@section('title', 'Personal Notes')
@section('content')
    <x-Adminlte.ContentWrapperComponent>
        <x-Adminlte.CardComponent>
            <p id="contentParagraph">Paragraph.</p>
        </x-Adminlte.CardComponent>
        <x-Adminlte.CardComponent title="Comment" id="theForm" :asForm="true">
            <x-Adminlte.TableComponent id="theTable" />
            @auth
                <hr />
                <x-Form.InputForm name="comment" type="text" text="Comment" :required="true" :asTextarea="true" />
            @endauth
        </x-Adminlte.CardComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Init jquery
        $(document).ready(function(){
            // Load init function
            initDatatable();
            initComment();
        });

        // Init datatable
        function initDatatable(){
            // Get route with id placeholder
            const readRouteBase = `{{ route('fe.apps.notes.read', ['id' => '::ID::']) }}`;

            // Change id placeholder with the actual id
            let readRoute = readRouteBase.replace('::ID::', {{ request()->id }});

            // Server-side Datatable from API Endpoint
            $('#theTable').DataTable({
                ordering: false,
                processing: false,
                serverSide: false,
                ajax: {
                    type: 'GET',
                    url: readRoute,
                    dataSrc: function(response) {
                        // Populate first
                        $('#contentTitle').text(response.data.title);
                        $('#contentParagraph').html(response.data.content.replace(/\n/g, '<br>'));
                        
                        // Return comments for datatable
                        return response.data.has_many_comments;
                    },
                    error: function(response){
                        // API error
                        Swal.fire({
                            icon: 'error',
                            text: response.responseJSON.message,
                            allowOutsideClick: () => {
                                return false;
                            },
                        }).then(() => {
                            // Redirect
                            window.location.href = "{{ route('fe.page.index') }}";
                        });
                    }
                },
                columns: [
                    {
                        title: 'From', width: '25%', data: 'belongs_to_user.name',
                    },
                    {
                        title: 'Comment', width: '75%', data: 'comment',
                    },
                ],
            });
        }

        // Init comment
        function initComment(){
            // Get route with id placeholder
            const commentRouteBase = `{{ route('fe.apps.notes.comment', ['id' => '::ID::']) }}`;

            // Change id placeholder with the actual id
            let commentRoute = commentRouteBase.replace('::ID::', {{ request()->id }});

            // Handle form input while clearing previous action to avoid double submit
            $('#theForm').off('submit').on('submit', function(e){
                // Stop any possible unexpected default action
                e.preventDefault();

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Populate form data into single variable
                let formData = new FormData(this);

                // Handle ajax
                $.ajax({
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    url: commentRoute,
                    success: function(response){
                        // Handle success
                        if(response.success){
                            // Swal message
                            Swal.fire({
                                toast: true,
                                icon: 'success',
                                position: 'top-right',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false,
                            }).then(() => {
                                // Trigger reset button
                                $('#resetButton').trigger('click');

                                // Reload datatable
                                $('#theTable').DataTable().ajax.reload(null, false);
                            });
                        }
                        else{
                            // API error
                            Swal.fire({
                                icon: 'error',
                                text: response.message || 'Something went wrong.',
                            });
                        }
                    },
                    error: function(response){
                        // // Refresh page if session/csrf_token expired
                        if([200, 419].includes(response.status)){
                            Swal.fire({
                                icon: 'warning',
                                text: response.message || 'We encountered a fatal error. Please try reloading the page.',
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

                        console.log(errors);
                            
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
    </script>
@endpush