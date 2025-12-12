@extends('layouts.adminlte')
@section('title', 'Role')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="apps.rbac.role">
        <x-Adminlte.CardComponent id="theForm" :asForm="false" :upsert="true" title="Manage Role">
            <x-Adminlte.TableComponent id="theTable" />
        </x-Adminlte.CardComponent>
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" title="Manage Role">
            <x-Form.InputForm name="name" type="text" text="Name" :required="true" />
            <x-Form.SelectForm name="permission[]" text="Permission" :required="false" :multiple="true" />
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
        });

        // Handle overlay class for form processing state
        <x-Adminlte.ProcessingStateComponent type="modal" />

        // Init datatable
        function initDatatable(){
            // Server-side Datatable from API Endpoint
            <x-Adminlte.DatatableComponent id="theTable" :tableUrl="route('fe.apps.rbac.role.list')" method="GET">
                {
                    title: 'Name', data: 'name',
                },
                {
                    title: 'Permission', data: 'permissions',
                    render: function(data, type, row, meta){
                        if(Array.isArray(row.permissions) && row.permissions.length > 0){
                            return row.permissions.map(function(data){
                                return `<span class="badge badge-pill badge-secondary">${ data.name }</span>`;
                            }).join(' ');
                        }

                        return '-';
                    },
                },
            </x-Adminlte.DatatableComponent>
        }

        // Init datatable
        function initUpsert(){
            // Init upsert (Update or Insert)
            $('body').on('click', '#btn-upsert', function(){
                // Open modal
                $('#theModalModal').modal('show');

                // Reset the form
                $('#buttonResetModal').trigger('click');

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Get data id
                let dataID = $(this).data('id');

                // Handle insert
                if(!dataID){
                    // Define null varPermission
                    varPermission = [];

                    // Reset role name readonly property
                    $('#name').prop('readonly', false);

                    // Populate list
                    loadPermission();

                    // Rename modal title
                    $('#theModalLabel').text('Add Role');

                    // Set route action
                    routeAction = `{{ route('fe.apps.rbac.role.store') }}`;
                    
                    // Set HTTP method for create (default POST)
                    routeMethod = 'POST';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" :asModal="true" />
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Sync Role to Permission');

                    // Get route with id placeholder
                    const readRouteBase = `{{ route('fe.apps.rbac.role.show', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    let readRoute = readRouteBase.replace('::ID::', dataID);

                    // Handle form populate
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: readRoute,
                        success: function(response){
                            // Handle populated "<select>" input
                            varPermission = response.data.permissions.map(permission => permission.name);

                            // Manual populate
                            $('#name').val(response.data.name);

                            // Role name can not be changed directly
                            $('#name').prop('readonly', true);

                            // Populate list
                            loadPermission();
                        }
                    });

                    // Get route with id placeholder
                    const routeBase = `{{ route('fe.apps.rbac.role.sync_to_permission', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    routeAction = routeBase.replace('::ID::', dataID);
                    
                    // Set HTTP method for sync (POST for custom action)
                    routeMethod = 'POST';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" :asModal="true" />
                }
            });
        }

        // Load permission
        function loadPermission(){
            // By default when loading the permission, the state of form processing is set as true
            setProcessingState(true);

            // Handle permission list
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: `{{ route('fe.apps.rbac.permission.list') }}`,
                success: function(response){
                    // Select input
                    const select = $('[name="permission[]"');

                    // Clear existing options first
                    select.empty().append('<option value="">Select an Option</option>');

                    // Get selected permission
                    const selectedPermissions = varPermission ? varPermission : [];

                    // Map data
                    response.forEach(function(data){
                        // Append data
                        select.append($('<option>', {
                            value: data.name,
                            text: data.name,
                            selected: selectedPermissions.includes(data.name),
                        }));
                    });

                    // After the permission is loaded, the state of form processing is set as true
                    setProcessingState(false);
                },
                error: function(){
                    $('#permission').html('<option value="">Error loading data...</option>');
                },
            });
        }
    </script>
@endpush