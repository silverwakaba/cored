@extends('layouts.adminlte')
@section('title', 'Permission')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="apps.rbac.permission">
        <x-Adminlte.CardComponent id="theForm" :asForm="false" :upsert="false" title="Filter Permission">
            <div class="row my-2">
                <div class="col-md-6">
                    <x-Form.InputForm name="filter-name" type="text" text="Permission Name" :required="false" />
                </div>
                <div class="col-md-6">
                    <x-Form.InputForm name="filter-role" type="text" text="Role Name" :required="false" />
                </div>
            </div>
        </x-Adminlte.CardComponent>
        <x-Adminlte.CardComponent id="theForm" :asForm="false" :upsert="true" title="Manage Permission">
            <x-Adminlte.TableComponent id="theTable" />
        </x-Adminlte.CardComponent>
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" :withCaptcha="false" title="Manage Permission">
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
            initWebsocket();
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
            <x-Adminlte.DatatableComponent id="theTable" :tableUrl="route('fe.apps.rbac.permission.list')" :deleteUrl="route('fe.apps.rbac.permission.destroy', ['id' => '::ID::'])" method="GET">
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
                    <x-Adminlte.FormComponent id="theModal" :asModal="true" />
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Edit Permission');

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
                        }
                    });

                    // Get route with id placeholder
                    let routeBase = `{{ route('fe.apps.rbac.permission.update', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    routeAction = routeBase.replace('::ID::', dataID);
                    
                    // Set HTTP method for update
                    routeMethod = 'PUT';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" :asModal="true" />
                }
            });
        }
    </script>
@endpush