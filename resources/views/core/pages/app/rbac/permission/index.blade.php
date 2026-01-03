@extends('layouts.adminlte')
@section('title', 'Permission')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="apps.rbac.permission">
        <x-Adminlte.CardComponent id="theFilter" :asForm="false" title="Filter Permission">
            <div class="row my-2">
                <div class="col-md-12">
                    <x-Form.SelectForm name="filter-role[]" text="Role" :required="false" :multiple="true" />
                </div>
            </div>
        </x-Adminlte.CardComponent>
        <x-Adminlte.CardComponent id="theForm" :asForm="false" title="Manage Permission">
            <x-Adminlte.TableComponent id="theTable" />
        </x-Adminlte.CardComponent>
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" :withCaptcha="false" title="Manage Permission">
            <x-Form.InputForm name="name" type="text" text="Name" :required="true" />
        </x-Adminlte.ModalComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Define usable variable
        let varPermission;
        let routeAction;
        
        // Init jquery
        $(document).ready(function(){
            // Load init function
            initDatatable();
            initUpsert();
            initWebsocket();
            loadRole();
        });

        // Init websocket
        function initWebsocket(){
            // Websocket channel
            let websocket = Echo.channel('generalChannel');
            
            // Listen to websocket
            websocket.listen('.generalEvent', function(data){
                $('#theTable').DataTable().ajax.reload(null, false);
            });
        }

        // Handle overlay class for form processing state
        <x-Adminlte.ProcessingStateComponent type="modal" />

        // Init datatable
        function initDatatable(){
            // Server-side Datatable from API Endpoint
            <x-Adminlte.DatatableComponent id="theTable" :tableUrl="route('fe.apps.rbac.permission.list')" :deleteUrl="route('fe.apps.rbac.permission.destroy', ['id' => '::ID::'])" :upsert="true" :editable="true" :filterable="true" :reloadable="true" method="GET">
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
                    // Define null varPermission
                    varPermission = [];

                    // Rename modal title
                    $('#theModalLabel').text('Add Permission');

                    // Set route action
                    routeAction = `{{ route('fe.apps.rbac.permission.store') }}`;
                    
                    // Set HTTP method for create (default POST)
                    routeMethod = 'POST';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" table="theTable" :asModal="true" />
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Edit Permission');

                    // Set processing state to true before loading data
                    setProcessingState(true);

                    // Get route with id placeholder
                    let readRouteBase = `{{ route('fe.apps.rbac.permission.show', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    readRoute = readRouteBase.replace('::ID::', dataID);

                    // Handle form populate
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: readRoute,
                        success: function(response){
                            // Manual populate
                            $('#name').val(response.data.name);

                            // After data is loaded, disable processing state
                            setProcessingState(false);
                        },
                        error: function(){
                            // On error, disable processing state
                            setProcessingState(false);
                        }
                    });

                    // Get route with id placeholder
                    let routeBase = `{{ route('fe.apps.rbac.permission.update', ['id' => '::ID::']) }}`;

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
        function loadRole(){
            // Handle permission list
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: `{{ route('fe.apps.rbac.role.list') }}`,
                success: function(response){
                    // Select input
                    const select = $(`[name="filter-role[]"]`);

                    // Clear existing options first
                    select.empty().append('<option value="">Select an Option</option>');

                    // Map data
                    response.forEach(function(data){
                        // Append data
                        select.append($('<option>', {
                            value: data.name,
                            text: data.name,
                        }));
                    });
                },
                error: function(){
                    $(`[name="filter-role[]"]`).html('<option value="">Error loading data...</option>');
                },
            });
        }
    </script>
@endpush