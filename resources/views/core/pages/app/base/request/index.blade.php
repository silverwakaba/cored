@extends('layouts.adminlte')
@section('title', 'Request')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="apps.base.request">
        <x-Adminlte.CardComponent id="theFilter" :asForm="false" title="Filter Request">
            <div class="row my-2">
                <div class="col-md-6">
                    <x-Form.SelectForm name="filter-active" text="Active Status" :required="false" :multiple="false" />
                </div>
                <div class="col-md-6">
                    <x-Form.SelectForm name="filter-module" text="Module" :required="false" :multiple="false" />
                </div>
            </div>
        </x-Adminlte.CardComponent>
        <x-Adminlte.CardComponent id="theForm" :asForm="false" title="Manage Request">
            <x-Adminlte.TableComponent id="theTable" />
        </x-Adminlte.CardComponent>
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" :withCaptcha="false" title="Manage Request">
            <x-Form.InputForm name="name" type="text" text="Name" :required="true" />
            <x-Form.SelectForm name="module" text="Module" :required="true" :multiple="false" />
        </x-Adminlte.ModalComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Define usable variable
        let varModule;
        let routeAction;

        // Init jquery
        $(document).ready(function(){
            // Load init function
            initDatatable();
            initUpsert();
            initWebsocket();
            loadBoolean();
            loadModule();
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
            <x-Adminlte.DatatableComponent id="theTable" :tableUrl="route('fe.apps.base.request.list')" :deleteUrl="route('fe.apps.base.request.destroy', ['id' => '::ID::'])" :upsert="true" :editable="true" :filterable="true" method="GET">
                {
                    title: 'Name', data: 'name',
                },
                {
                    title: 'Module', data: 'baseModule',
                    render: function(data, type, row, meta){
                        if(row.base_module && row.base_module.name){
                            return `<span class="badge badge-pill badge-secondary">${ row.base_module.name }</span>`;
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
                    // Define variable
                    varModule = null;

                    // Populate module list
                    loadModule('module', true, true);

                    // Rename modal title
                    $('#theModalLabel').text('Add Request');

                    // Set route action
                    routeAction = `{{ route('fe.apps.base.request.store') }}`;
                    
                    // Set HTTP method for create (default POST)
                    routeMethod = 'POST';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" :asModal="true" />
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Edit Request');

                    // Get route with id placeholder
                    let readRouteBase = `{{ route('fe.apps.base.request.show', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    readRoute = readRouteBase.replace('::ID::', dataID);

                    // Handle form populate
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: readRoute,
                        success: function(response){
                            // Handle populated "<select>" input
                            varModule = response.data.base_module.id;

                            // Manual populate
                            $('#name').val(response.data.name);

                            // Populate module list
                            loadModule('module', true, true);
                        }
                    });

                    // Get route with id placeholder
                    let routeBase = `{{ route('fe.apps.base.request.update', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    routeAction = routeBase.replace('::ID::', dataID);
                    
                    // Set HTTP method for update
                    routeMethod = 'PUT';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" :asModal="true" />
                }
            });
        }

        // Load boolean
        function loadBoolean(){
            // Handle role list
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: `{{ route('fe.apps.base.general.boolean') }}`,
                success: function(response){
                    // Select input
                    const select = $('[name="filter-active"');

                    // Clear existing options first
                    select.empty().append('<option value="">Select an Option</option>');

                    // Map data
                    response.forEach(function(data){
                        // Format text with description
                        let optionText = data.text;
                        if(data.text === 'Yes' || data.value === true || data.value === 'true' || data.value === 1){
                            optionText = 'Yes (Active Data)';
                        } else if(data.text === 'No' || data.value === false || data.value === 'false' || data.value === 0){
                            optionText = 'No (Inactive Data)';
                        }
                        
                        // Append data
                        select.append($('<option>', {
                            value: data.value,
                            text: optionText,
                        }));
                    });
                },
                error: function(){
                    $('#filter-active').html('<option value="">Error loading data...</option>');
                },
            });
        }

        // Load module
        function loadModule(targetSelector = 'filter-module', useProcessingState = false, onlyActive = false){
            // By default when loading the module, the state of form processing is set as true
            if(useProcessingState){
                setProcessingState(true);
            }

            // Build URL based on onlyActive parameter
            let moduleUrl;
            if(!onlyActive){
                moduleUrl = `{{ route('fe.apps.base.module.list') }}`;
            } else {
                moduleUrl = `{{ route('fe.apps.base.module.list', ['filter-active' => true]) }}`;
            }

            // Handle role list
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: moduleUrl,
                success: function(response){
                    // Select input berdasarkan target selector
                    const select = $(`[name="${targetSelector}"]`);

                    // Clear existing options first
                    select.empty().append('<option value="">Select an Option</option>');

                    // Get selected module
                    const selectedModule = varModule ? varModule : null;

                    // Map data
                    response.forEach(function(data){
                        // Append data
                        select.append($('<option>', {
                            value: data.id,
                            text: data.name,
                            selected: selectedModule !== null && selectedModule == data.id,
                        }));
                    });

                    // After the module is loaded, the state of form processing is set as false
                    if(useProcessingState){
                        setProcessingState(false);
                    }
                },
                error: function(){
                    $(`[name="${targetSelector}"]`).html('<option value="">Error loading data...</option>');
                },
            });
        }
    </script>
@endpush