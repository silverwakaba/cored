
@extends('layouts.adminlte')
@section('title', 'User Access Control')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="apps.rbac.uac">
        <x-Adminlte.CardComponent id="theForm" :asForm="false" title="Manage User Access Control">
            <x-Adminlte.TableComponent id="theTable" />
        </x-Adminlte.CardComponent>
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" title="Manage User Access Control">
            <x-Form.InputForm name="name" type="text" text="Name" :required="true" />
            <x-Form.InputForm name="email" type="email" text="Email" :required="true" />
            <x-Form.SelectForm name="role[]" text="Role" :required="false" :multiple="true" />
        </x-Adminlte.ModalComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Define usable variable
        let varRole;
        let routeAction;
        
        // Init jquery
        $(document).ready(function(){
            // Load init function
            initDatatable();
            initUpsert();
            initActivation();
        });

        // Handle overlay class for form processing state
        <x-Adminlte.ProcessingStateComponent type="modal" />

        // Init datatable
        function initDatatable(){
            // Server-side Datatable from API Endpoint
            <x-Adminlte.DatatableComponent id="theTable" :tableUrl="route('fe.apps.rbac.uac.list')" :upsert="true" :editable="true" :filterable="true" method="GET">
                {
                    title: 'Name', data: 'name',
                },
                {
                    title: 'Email', data: 'email',
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
            </x-Adminlte.DatatableComponent>
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
                    // Define null varRole
                    varRole = [];

                    // Reset role name readonly property
                    $('#email').prop('readonly', false);

                    // Populate list
                    loadRole();

                    // Rename modal title
                    $('#theModalLabel').text('Add User');

                    // Set route action
                    routeAction = `{{ route('fe.apps.rbac.uac.store') }}`;
                    
                    // Set HTTP method for create (default POST, but explicit for clarity)
                    routeMethod = 'POST';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" :asModal="true" />
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Edit User');

                    // Get route with id placeholder
                    const readRouteBase = `{{ route('fe.apps.rbac.uac.show', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    let readRoute = readRouteBase.replace('::ID::', dataID);

                    // Handle form populate
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: readRoute,
                        success: function(response){
                            // Handle populated "<select>" input
                            varRole = response.data.roles.map(role => role.name);

                            // Manual populate
                            $('#name').val(response.data.name);
                            $('#email').val(response.data.email);

                            // Email can not be changed directly
                            $('#email').prop('readonly', true);

                            // Populate list
                            loadRole();
                        }
                    });

                    // Get route with id placeholder
                    const routeBase = `{{ route('fe.apps.rbac.uac.update', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    routeAction = routeBase.replace('::ID::', dataID);
                    
                    // Set HTTP method for update
                    routeMethod = 'PUT';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" :asModal="true" />
                }
            });
        }

        // Load role
        function loadRole(){
            // By default when loading the role, the state of form processing is set as true
            setProcessingState(true);

            // Handle role list
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: `{{ route('fe.apps.rbac.role.list') }}`,
                success: function(response){
                    // Select input
                    const select = $('[name="role[]"');

                    // Clear existing options first
                    select.empty().append('<option value="">Select an Option</option>');

                    // Get selected role
                    const selectedRoles = varRole ? varRole : [];

                    // Map data
                    response.forEach(function(data){
                        // Append data
                        select.append($('<option>', {
                            value: data.name,
                            text: data.name,
                            selected: selectedRoles.includes(data.name),
                        }));
                    });

                    // After the role is loaded, the state of form processing is set as true
                    setProcessingState(false);
                },
                error: function(){
                    $('#role').html('<option value="">Error loading data...</option>');
                },
            });
        }

        // Init activation
        function initActivation(){
            // Init delete
            $('body').on('click', '#btn-activation', function(){
                // Get data id
                let dataID = $(this).data('id');

                // Get data prop
                let dataIsActive = $(this).data('isactive');

                // Show confirmation
                Swal.fire({
                    icon: 'warning',
                    text: 'Are you sure?',
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
                        const routeBase = `{{ route('fe.apps.rbac.uac.activation', ['id' => '::ID::']) }}`;

                        // Change id placeholder with the actual id
                        routeAction = routeBase.replace('::ID::', dataID);

                        // Handle ajax
                        $.ajax({
                            type: 'POST',
                            url: routeAction,
                            dataType: 'json',
                            data: {
                                'is_active': dataIsActive,
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(response){
                                // Handle success
                                if(response.success){
                                    // Success
                                    Swal.fire({
                                        icon: 'success',
                                        text: response.message || response.responseJSON.message || 'Something went wrong.',
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
                                        text: response.message || response.responseJSON.message || 'Something went wrong.',
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
                                        text: response.message || response.responseJSON.message || 'Something went wrong.',
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
    </script>
@endpush