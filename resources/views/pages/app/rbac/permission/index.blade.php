@extends('layouts.adminlte')
@section('title', 'Permission')
@section('content')
    <x-Adminlte.ContentWrapperComponent>
        <x-Adminlte.CardComponent id="theForm" :asForm="false" :upsert="true" title="Manage Permission">
            <x-Adminlte.TableComponent id="theTable" />
        </x-Adminlte.CardComponent>
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" title="Manage Permission">
            <x-Form.InputForm name="name" type="text" text="Name" :required="true" />
        </x-Adminlte.ModalComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Init jquery
        $(document).ready(function(){
            // Define usable variable
            let varPermission;
            let routeAction;

            // Load init function
            initDatatable();
            initUpsert();
            initDelete();
        });

        // Init datatable
        function initDatatable(){
            // Server-side Datatable from API Endpoint
            $('#theTable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                searchDelay: 1500,
                ajax: {
                    type: 'GET',
                    data: function(d){
                        // Pass parameter
                        d.type = 'datatable';
                    },
                    url: `{{ route('fe.apps.permission.list') }}`,
                    error: function(response){
                        // API error
                        Swal.fire({
                            icon: 'warning',
                            text: response.responseJSON.message,
                            allowOutsideClick: () => {
                                return false;
                            },
                        });
                    }
                },
                columns: [
                    {
                        title: 'No.', width: '5%', class: 'text-center',
                        render: function(data, type, row, meta){
                            return `${ meta.row + meta.settings._iDisplayStart + 1 }`;
                        },
                    },
                    {
                        title: 'Name', data: 'name',
                    },
                    {
                        title: 'Roles', data: 'roles',
                        render: function(data, type, row, meta){
                            if(Array.isArray(row.roles) && row.roles.length > 0){
                                return row.roles.map(function(data){
                                    return `<span class="badge badge-pill badge-secondary">${ data.name }</span>`;
                                }).join(' ');
                            }

                            return '-';
                        },
                    },
                    {
                        title: 'Action', width: '10%', class: 'text-center',
                        render: function(data, type, row, meta){
                            return `
                                <div class="btn-group btn-block" role="group">
                                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                                    <div class="dropdown-menu btn-block">
                                        <button id="btn-upsert" class="dropdown-item" data-id="${ row.id }"><i class="fas fa-pen-to-square mr-2"></i>Edit</button>
                                        <button id="btn-delete" class="dropdown-item" data-id="${ row.id }"><i class="fas fa-trash mr-2"></i>Delete</button>
                                    </div>
                                </div>
                            `;
                        },
                    },
                ],
            });
        }

        // Init upsert
        function initUpsert(){
            // Init upsert (Update or Insert)
            $('body').on('click', '#btn-upsert', function(){
                // Get data id
                let dataID = $(this).data('id');

                // Open modal
                $('#theModalModal').modal('show');

                // Reset the form
                $('#buttonResetModal').trigger('click');

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Handle insert
                if(!dataID){
                    // Define null varPermission
                    varPermission = [];

                    // Rename modal title
                    $('#theModalLabel').text('Add Permission');

                    // Set route action
                    routeAction = `{{ route('fe.apps.permission.create') }}`;

                    // Init form action
                    formAction(routeAction);
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Edit Permission');

                    // Get route with id placeholder
                    const readRouteBase = `{{ route('fe.apps.permission.read', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    let readRoute = readRouteBase.replace('::ID::', dataID);

                    // Handle form populate
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: readRoute,
                        success: function(response){
                            // Manual populate
                            $('#name').val(response.data.name);
                        }
                    });

                    // Get route with id placeholder
                    const routeBase = `{{ route('fe.apps.permission.update', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    routeAction = routeBase.replace('::ID::', dataID);

                    // Init form action
                    formAction(routeAction);
                }
            });
        }

        // Form action
        function formAction(route){
            // Handle form input while clearing previous action to avoid double submit
            $('#theModal').off('submit').on('submit', function(e){
                // Stop any possible unexpected default action
                e.preventDefault();

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // By default when submitting the form, the state of form processing is set as true
                setProcessingState(true);

                // Populate form data into single variable
                let formData = new FormData(this);

                // Handle ajax
                $.ajax({
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    url: route,
                    success: function(response){
                        // Handle success
                        if(response.success){
                            // If success, form processing state is set as true to avoid duplication
                            setProcessingState(true);

                            // Success
                            Swal.fire({
                                icon: 'success',
                                text: response.message,
                                allowOutsideClick: () => {
                                    return false;
                                },
                            }).then(() => {
                                // Trigger reset button
                                $('#buttonResetModal').trigger('click');
                                
                                // Hide modal
                                $('#theModalModal').modal('hide');

                                // Reload datatable
                                $('#theTable').DataTable().ajax.reload(null, false);

                                // Then reset form processing state
                                setProcessingState(false);
                            });
                        }
                        else{
                            // API error
                            Swal.fire({
                                icon: 'error',
                                text: response.message || 'Something went wrong.',
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

        // Init delete
        function initDelete(){
            // Init delete
            $('body').on('click', '#btn-delete', function(){
                // Get data id
                let dataID = $(this).data('id');

                // Show confirmation
                Swal.fire({
                    icon: 'warning',
                    text: 'Are you sure? This action cannot be undone.',
                    focusDeny: true,
                    showConfirmButton: true,
                    showDenyButton: true,
                    denyButtonText: 'No',
                    confirmButtonText: 'Yes',
                    allowOutsideClick: () => {
                        return false;
                    },
                }).then((result) => {
                    if(result.isConfirmed){
                        // Get route with id placeholder
                        const routeBase = `{{ route('fe.apps.permission.delete', ['id' => '::ID::']) }}`;

                        // Change id placeholder with the actual id
                        routeAction = routeBase.replace('::ID::', dataID);

                        // Handle ajax
                        $.ajax({
                            type: 'POST',
                            url: routeAction,
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(response){
                                // Handle success
                                if(response.success){
                                    // Success
                                    Swal.fire({
                                        icon: 'success',
                                        text: response.message,
                                        allowOutsideClick: () => {
                                            return false;
                                        },
                                    }).then(() => {
                                        // Reload datatable
                                        $('#theTable').DataTable().ajax.reload(null, false);
                                    });
                                }
                                else{
                                    // API error
                                    Swal.fire({
                                        icon: 'error',
                                        text: response.message || 'Something went wrong.',
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
                            }
                        });
                    }
                });
            });
        }

        // Handle overlay class for form processing state
        function setProcessingState(processing){
            const reset = $('#buttonResetModal');
            const submit = $('#buttonSubmitModal');
            const overlay = $('#overlay-modal');

            if(processing){
                reset.prop('disabled', true);

                submit.prop('disabled', true);

                overlay.addClass('overlay').removeClass('d-none');
            }
            else{
                reset.prop('disabled', false);

                submit.prop('disabled', false);

                overlay.addClass('d-none').removeClass('overlay');
            }
        }
    </script>
@endpush