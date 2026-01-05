
@extends('layouts.adminlte')
@section('title', 'User Access Control')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="apps.rbac.uac">
        <x-Adminlte.CardComponent id="theFilter" :asForm="false" title="Filter Permission">
            <div class="row my-2">
                <div class="col-md-12">
                    <x-Form.SelectForm name="filter-role[]" text="Role" :required="false" :multiple="true" />
                </div>
            </div>
        </x-Adminlte.CardComponent>
        <x-Adminlte.CardComponent id="theForm" :asForm="false" title="Manage User Access Control">
            <x-Adminlte.TableComponent id="theTable" />
        </x-Adminlte.CardComponent>
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" title="Manage User Access Control">
            <x-Form.InputForm name="name" type="text" text="Name" :required="true" />
            <x-Form.InputForm name="email" type="email" text="Email" :required="true" />
            <x-Form.SelectForm name="role[]" text="Role" :required="true" :multiple="true" />
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
            loadRole();
        });

        // Handle overlay class for form processing state
        <x-Adminlte.ProcessingStateComponent type="modal" />

        // Init datatable
        function initDatatable(){
            // Server-side Datatable from API Endpoint
            <x-Adminlte.DatatableComponent id="theTable" :tableUrl="route('fe.apps.rbac.uac.list')" :deleteUrl="route('fe.apps.rbac.uac.destroy', ['id' => '::ID::'])" :upsert="true" :editable="true" :filterable="true" method="GET">
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
                    loadRole('role[]', true);

                    // Rename modal title
                    $('#theModalLabel').text('Add User');

                    // Set route action
                    routeAction = `{{ route('fe.apps.rbac.uac.store') }}`;
                    
                    // Set HTTP method for create (default POST, but explicit for clarity)
                    routeMethod = 'POST';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" table="theTable" :asModal="true" />
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Edit User');

                    // Set processing state to true before loading data
                    setProcessingState(true);

                    // Get route with id placeholder
                    const readRouteBase = `{{ route('fe.apps.rbac.uac.show', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    let readRoute = readRouteBase.replace('::ID::', dataID);

                    // Counter to track completed AJAX calls
                    let completedCalls = 0;
                    const totalCalls = 2; // user data, role

                    // Function to check if all calls are completed
                    function checkAllComplete(){
                        completedCalls++;
                        if(completedCalls >= totalCalls){
                            setProcessingState(false);
                        }
                    }

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

                            // Populate list and mark as complete after role is loaded
                            loadRole('role[]', false, checkAllComplete);

                            // Mark user data call as complete
                            checkAllComplete();
                        },
                        error: function(){
                            // On error, still mark as complete and disable processing state
                            checkAllComplete();
                        }
                    });

                    // Get route with id placeholder
                    const routeBase = `{{ route('fe.apps.rbac.uac.update', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    routeAction = routeBase.replace('::ID::', dataID);
                    
                    // Set HTTP method for update
                    routeMethod = 'PUT';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" table="theTable" :asModal="true" />
                }
            });
        }

        // Load role
        function loadRole(targetSelector = 'filter-role[]', useProcessingState = false, onComplete = null){
            // By default when loading the role, the state of form processing is set as true
            if(useProcessingState){
                setProcessingState(true);
            }

            // Handle role list
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: `{{ route('fe.apps.rbac.role.list') }}`,
                success: function(response){
                    // Select input
                    const select = $(`[name="${targetSelector}"]`);

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
                            selected: Array.isArray(selectedRoles) && selectedRoles.includes(data.name),
                        }));
                    });

                    // After the role is loaded, call onComplete if provided
                    if(onComplete && typeof onComplete === 'function'){
                        onComplete();
                    }

                    // After the role is loaded, the state of form processing is set as false
                    if(useProcessingState){
                        setProcessingState(false);
                    }
                },
                error: function(){
                    $(`[name="${targetSelector}"]`).html('<option value="">Error loading data...</option>');
                    
                    // Call onComplete even on error
                    if(onComplete && typeof onComplete === 'function'){
                        onComplete();
                    }
                },
            });
        }
    </script>
@endpush