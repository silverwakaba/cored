@extends('layouts.adminlte')
@section('title', 'Menu')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="apps.menu">
        <x-Adminlte.CardComponent id="theForm" :asForm="false" title="Manage Menu">
            <x-Adminlte.TableComponent id="theTable" />
        </x-Adminlte.CardComponent>
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" :withCaptcha="false" title="Manage Menu">
            <x-Form.InputForm name="name" type="text" text="Name" :required="true" />
            <x-Form.InputForm name="icon" type="text" text="Icon" :required="false" />
            <x-Form.InputForm name="route" type="text" text="Route" :required="false" />
            <x-Form.SelectForm name="type" text="Type" :required="true" :multiple="false" />
            <x-Form.SelectForm name="parent" text="Parent" :required="false" :multiple="false" />
            <x-Form.SelectForm name="authenticate" text="Require Authentication" :required="false" :multiple="false" />
            <x-Form.SelectForm name="guest_only" text="Guest Only" :required="false" :multiple="false" />
            <x-Form.SelectForm name="position" text="Position" :required="false" :multiple="false" />
            <x-Form.SelectForm name="reference_id" text="Reference Menu" :required="false" :multiple="false" />
        </x-Adminlte.ModalComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Define usable variable
        let varMenu;
        let routeAction;
        
        // Reset select forms
        function resetSelectForms(){
            // Use setTimeout to ensure Select2 is ready
            setTimeout(function(){
                // Reset type select (Select2)
                $('#type').val('').trigger('change.select2');
                
                // Reset parent select (Select2)
                $('#parent').val('').trigger('change.select2');
                
                // Reset authenticate select (Select2)
                $('#authenticate').val('').trigger('change.select2');
                
                // Reset guest_only select (Select2)
                $('#guest_only').val('').trigger('change.select2');
                
                // Reset position select (Select2)
                $('#position').val('').trigger('change.select2');
                
                // Reset reference_id select (Select2)
                $('#reference_id').val('').trigger('change.select2');
            }, 100);
        }
        
        // Init jquery
        $(document).ready(function(){
            // Load init function
            initDatatable();
            initUpsert();
            loadType();
            loadParent();
            loadBoolean();

            // Listen for form success to reset select forms
            // Listen for modal hidden event (after form success)
            $('#theModalModal').on('hidden.bs.modal', function(){
                resetSelectForms();
            });

            // Also reset after form reset button is clicked
            $('body').on('click', '#buttonResetModal', function(){
                resetSelectForms();
            });
        });

        // Handle overlay class for form processing state
        <x-Adminlte.ProcessingStateComponent type="modal" />

        // Init datatable
        function initDatatable(){
            // Server-side Datatable from API Endpoint
            <x-Adminlte.DatatableComponent id="theTable" :tableUrl="route('fe.apps.menu.list')" :deleteUrl="route('fe.apps.menu.destroy', ['id' => '::ID::'])" :upsert="true" :editable="true" :filterable="true" :reloadable="true" method="GET">
                {
                    title: 'Name', data: 'name',
                },
                {
                    title: 'Type', data: 'type',
                    render: function(data, type, row, meta){
                        const typeMap = {
                            'h': '<span class="badge badge-pill badge-primary">Header</span>',
                            'p': '<span class="badge badge-pill badge-info">Parent</span>',
                            'c': '<span class="badge badge-pill badge-secondary">Child</span>',
                        };
                        return typeMap[data] || data;
                    },
                },
                {
                    title: 'Icon', data: 'icon',
                    render: function(data, type, row, meta){
                        return data ? `<i class="${data}"></i>` : '-';
                    },
                },
                {
                    title: 'Route', data: 'route',
                    render: function(data, type, row, meta){
                        return data || '-';
                    },
                },
                {
                    title: 'Parent', data: 'parent',
                    render: function(data, type, row, meta){
                        return data ? data.name : '-';
                    },
                },
                {
                    title: 'Auth', data: 'is_authenticate',
                    render: function(data, type, row, meta){
                        return data ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>';
                    },
                },
                {
                    title: 'Guest', data: 'is_guest_only',
                    render: function(data, type, row, meta){
                        return data ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>';
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
                    // Define null varMenu
                    varMenu = [];

                    // Rename modal title
                    $('#theModalLabel').text('Add Menu');

                    // Set route action
                    routeAction = `{{ route('fe.apps.menu.store') }}`;
                    
                    // Set HTTP method for create (default POST)
                    routeMethod = 'POST';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" table="theTable" :asModal="true" />
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Edit Menu');

                    // Get route with id placeholder
                    let readRouteBase = `{{ route('fe.apps.menu.show', ['id' => '::ID::']) }}`;

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
                            $('#icon').val(response.data.icon || '');
                            $('#route').val(response.data.route || '');
                            $('#type').val(response.data.type).trigger('change');
                            
                            // Load parent options and set value
                            setTimeout(function(){
                                $('#parent').val(response.data.parent_id || '').trigger('change');
                            }, 500);
                            
                            $('#authenticate').val(response.data.is_authenticate ? '1' : '0').trigger('change');
                            $('#guest_only').val(response.data.is_guest_only ? '1' : '0').trigger('change');
                        }
                    });

                    // Get route with id placeholder
                    let routeBase = `{{ route('fe.apps.menu.update', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    routeAction = routeBase.replace('::ID::', dataID);
                    
                    // Set HTTP method for update
                    routeMethod = 'PUT';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" table="theTable" :asModal="true" />
                }
            });
        }

        // Load type
        function loadType(){
            const typeOptions = [
                { value: 'h', text: 'Header' },
                { value: 'p', text: 'Parent' },
                { value: 'c', text: 'Child' }
            ];

            // Type select
            const typeSelect = $('#type');
            typeSelect.empty().append('<option value="">Select an Option</option>');
            typeOptions.forEach(function(option){
                typeSelect.append($('<option>', {
                    value: option.value,
                    text: option.text
                }));
            });
        }

        // Load parent
        function loadParent(){
            // Handle parent list - filter headers and parents (type 'h' and 'p') in frontend
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: `{{ route('fe.apps.menu.list') }}`,
                success: function(response){
                    // Parent select
                    const parentSelect = $('#parent');
                    parentSelect.empty().append('<option value="">Select an Option (None for Header)</option>');

                    // Helper function to process data (handle both array and object)
                    function processData(data){
                        if(!data) return [];
                        
                        // If it's already an array
                        if(Array.isArray(data)){
                            return data;
                        }
                        
                        // If it's an object, try to extract array
                        if(typeof data === 'object'){
                            // Check if it has data property
                            if(data.data && Array.isArray(data.data)){
                                return data.data;
                            }
                            // Check if it's a collection-like object
                            if(data.length !== undefined){
                                return Array.from(data);
                            }
                            // Try Object.values
                            const values = Object.values(data);
                            if(values.length > 0 && typeof values[0] === 'object'){
                                return values;
                            }
                        }
                        
                        return [];
                    }

                    // Get data array - handle different response formats
                    let dataArray = [];
                    
                    // Check if response has data property (standard API response)
                    if(response.data !== undefined){
                        dataArray = processData(response.data);
                    }
                    // Check if response is directly an array
                    else if(Array.isArray(response)){
                        dataArray = response;
                    }
                    // Check if response has success property with data
                    else if(response.success && response.data){
                        dataArray = processData(response.data);
                    }
                    // Try to process response directly
                    else {
                        dataArray = processData(response);
                    }

                    // Filter and map only headers and parents
                    if(dataArray && dataArray.length > 0){
                        let addedCount = 0;
                        dataArray.forEach(function(data){
                            // Only show headers and parents as parent options
                            if(data && data.id && (data.type === 'h' || data.type === 'p')){
                                parentSelect.append($('<option>', {
                                    value: data.id,
                                    text: `${data.name || 'Unnamed'} (${data.type === 'h' ? 'Header' : 'Parent'})`
                                }));
                                addedCount++;
                            }
                        });
                    }
                },
                error: function(xhr, status, error){
                    $('#parent').html('<option value="">Error loading data...</option>');
                },
            });
        }

        // Load boolean
        function loadBoolean(){
            const booleanOptions = [
                { value: '0', text: 'No' },
                { value: '1', text: 'Yes' }
            ];

            // Is authenticate select
            const isAuthSelect = $('#authenticate');
            isAuthSelect.empty().append('<option value="">Select an Option</option>');
            booleanOptions.forEach(function(option){
                isAuthSelect.append($('<option>', {
                    value: option.value,
                    text: option.text
                }));
            });

            // Is guest only select
            const isGuestSelect = $('#guest_only');
            isGuestSelect.empty().append('<option value="">Select an Option</option>');
            booleanOptions.forEach(function(option){
                isGuestSelect.append($('<option>', {
                    value: option.value,
                    text: option.text
                }));
            });

            // Position select
            const positionSelect = $('#position');
            positionSelect.empty().append('<option value="">Select an Option (Default: After)</option>');
            positionSelect.append($('<option>', { value: 'before', text: 'Before' }));
            positionSelect.append($('<option>', { value: 'after', text: 'After' }));

            // Reference menu select
            const referenceSelect = $('#reference_id');
            referenceSelect.empty().append('<option value="">Select an Option (Optional)</option>');
            
            // Load reference menu options
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: `{{ route('fe.apps.menu.list') }}`,
                success: function(response){
                    if(Array.isArray(response.data)){
                        response.data.forEach(function(data){
                            referenceSelect.append($('<option>', {
                                value: data.id,
                                text: `${data.name} (${data.type === 'h' ? 'Header' : data.type === 'p' ? 'Parent' : 'Child'})`
                            }));
                        });
                    }
                },
                error: function(){
                    referenceSelect.html('<option value="">Error loading data...</option>');
                },
            });
        }
    </script>
@endpush