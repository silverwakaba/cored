@extends('layouts.adminlte')
@section('title', 'Item')
@section('content')
    <x-Adminlte.ContentWrapperComponent>
        <x-Adminlte.CardComponent id="theFilter" :asForm="false" title="Filter Item">
            <div class="row my-2">
                <div class="col-md-12">
                    <x-Form.SelectForm name="filter-active" text="Active Status" :required="false" :multiple="false" />
                </div>
            </div>
        </x-Adminlte.CardComponent>
        <x-Adminlte.CardComponent id="theForm" :asForm="false" title="Manage Item">
            <x-Adminlte.TableComponent id="theTable" />
        </x-Adminlte.CardComponent>
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" :withCaptcha="false" title="Manage Item">
            <!-- Master -->
            <x-Form.InputForm name="name_master" type="text" text="Name Master" :required="true" />
            <x-Form.InputForm name="description_master" type="text" text="Description Master" :required="true" />

            <!-- Detail -->
            <div class="form-group">
                <label>Item Details <span class="text-danger">*</span></label>
                <button type="button" class="btn btn-success btn-sm mb-2" id="btn-add-detail-row">
                    <i class="fa fa-plus"></i> Add Detail
                </button>
                <div class="table-responsive">
                    <table class="table table-bordered" id="detailTable">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Name Detail</th>
                                <th style="width: 50%;">Description Detail</th>
                                <th style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody">
                            <!-- Detail rows will be added here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </x-Adminlte.ModalComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Define usable variable
        let varPermission;
        let routeAction;
        let detailRowIndex = 0;
        
        // Init jquery
        $(document).ready(function(){
            // Load init function
            initDatatable();
            initUpsert();
            initWebsocket();
            loadBoolean();
            initDetailRows();
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
            <x-Adminlte.DatatableComponent
                id="theTable"
                :tableUrl="route('fe.apps.base.item.list')"
                :deleteUrl="route('fe.apps.base.item.destroy', ['id' => '::ID::'])"
                :upsert="true"
                :editable="true"
                :filterable="true"
                :selectable="true"
                :selectMode="'multiple'"
                :bulkActions="[
                    [
                        'text'      => 'Activate or Deactivate',
                        'icon'      => 'fa-trash',
                        'action'    => 'delete',
                        'url'       => route('fe.apps.base.item.bulk-destroy'),
                    ],
                ]"
                :reloadable="true"
                method="GET">
                {
                    title: 'Name', data: 'name',
                },
                {
                    title: 'Description', data: 'description',
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

                // Reset detail rows
                resetDetailRows();

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Handle insert
                if(!dataID){
                    // Rename modal title
                    $('#theModalLabel').text('Add Item');

                    // Reset detail rows - add one empty row
                    resetDetailRows();
                    addDetailRow();

                    // Set route action
                    routeAction = `{{ route('fe.apps.base.item.store') }}`;
                    
                    // Set HTTP method for create (default POST)
                    routeMethod = 'POST';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" table="theTable" :asModal="true" />
                    
                    // Hook into form error handling after form component is initialized
                    hookDetailErrorHandling();
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Edit Item');

                    // Set processing state to true before loading data
                    setProcessingState(true);

                    // Get route with id placeholder
                    let readRouteBase = `{{ route('fe.apps.base.item.show', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    readRoute = readRouteBase.replace('::ID::', dataID);

                    // Handle form populate
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: readRoute,
                        success: function(response){
                            // Manual populate master
                            $('#name_master').val(response.data.name);
                            $('#description_master').val(response.data.description);

                            // Reset and populate detail rows
                            resetDetailRows();
                            if(response.data.details && response.data.details.length > 0){
                                response.data.details.forEach(function(detail){
                                    addDetailRow(detail.name, detail.description);
                                });
                            } else {
                                // Add one empty row if no details exist
                                addDetailRow();
                            }

                            // After data is loaded, disable processing state
                            setProcessingState(false);
                        },
                        error: function(){
                            // On error, disable processing state
                            setProcessingState(false);
                        }
                    });

                    // Get route with id placeholder
                    let routeBase = `{{ route('fe.apps.base.item.update', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    routeAction = routeBase.replace('::ID::', dataID);
                    
                    // Set HTTP method for update
                    routeMethod = 'PUT';

                    // Init form action
                    <x-Adminlte.FormComponent id="theModal" table="theTable" :asModal="true" />
                    
                    // Hook into form error handling after form component is initialized
                    hookDetailErrorHandling();
                }
            });
        }

        // Hook into form component error handling
        function hookDetailErrorHandling(){
            console.log('hookDetailErrorHandling: Setting up error handler');
            
            // Use a more reliable approach - hook into AJAX complete event
            // This will fire after form component processes the error
            $(document).off('ajaxComplete.detailErrorHandler').on('ajaxComplete.detailErrorHandler', function(event, xhr, settings){
                console.log('ajaxComplete event fired', settings.url, xhr.status);
                
                // Only handle errors from item form submission
                if(settings && settings.url && (settings.url.includes('item.store') || settings.url.includes('item.update'))){
                    console.log('Item form error detected');
                    if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors){
                        console.log('422 error with response:', xhr.responseJSON.errors);
                        // Process detail errors after form component processes them
                        setTimeout(function(){
                            parseDetailErrors(xhr.responseJSON.errors);
                        }, 300);
                    }
                }
            });
        }
        
        // Also set up global handler on document ready as backup
        $(document).ready(function(){
            hookDetailErrorHandling();
        });

        // Init detail rows
        function initDetailRows(){
            // Add button click handler
            $(document).on('click', '#btn-add-detail-row', function(){
                addDetailRow();
            });

            // Remove button click handler (using event delegation)
            $(document).on('click', '.btn-remove-detail-row', function(){
                $(this).closest('tr').remove();
            });
        }

        // Reset detail rows
        function resetDetailRows(){
            $('#detailTableBody').empty();
            detailRowIndex = 0;
        }

        // Add detail row
        function addDetailRow(name = '', description = ''){
            detailRowIndex++;
            const rowIndex = detailRowIndex;
            const row = `
                <tr data-row-index="${rowIndex}">
                    <td>
                        <input type="text" 
                               name="details[${rowIndex}][name]" 
                               class="form-control detail-name" 
                               value="${name}" 
                               >
                        <div class="invalid-feedback detail-name-error"></div>
                    </td>
                    <td>
                        <input type="text" 
                               name="details[${rowIndex}][description]" 
                               class="form-control detail-description" 
                               value="${description}" 
                               >
                        <div class="invalid-feedback detail-description-error"></div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-detail-row">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#detailTableBody').append(row);
        }

        // Handle validation errors for detail rows
        function parseDetailErrors(errors){
            if(!errors) {
                console.log('parseDetailErrors: No errors provided');
                return;
            }
            
            console.log('parseDetailErrors: Processing errors', errors);
            
            // Handle detail validation errors
            // Laravel sends errors in format: details.0.name, details.0.description, etc.
            $.each(errors, function(key, value){
                // Check if this is a detail error (format: details.0.name, details.0.description, etc.)
                if(key.startsWith('details.')){
                    const parts = key.split('.');
                    if(parts.length === 3){
                        const backendIndex = parseInt(parts[1]); // Backend uses 0-based index (0, 1, 2, ...)
                        const field = parts[2]; // 'name' or 'description'
                        
                        console.log(`Processing error: details.${backendIndex}.${field} =`, value);
                        
                        // Find all rows in order (as they appear in DOM, which matches submission order)
                        const allRows = $('#detailTableBody tr');
                        console.log(`Found ${allRows.length} detail rows`);
                        
                        // Get the row at the backendIndex position
                        if(allRows.length > backendIndex){
                            const row = $(allRows[backendIndex]);
                            console.log(`Processing row at index ${backendIndex}`);
                            
                            // Find input by class (detail-name or detail-description)
                            const input = row.find(`input.detail-${field}`);
                            const errorDiv = row.find(`div.detail-${field}-error`);
                            
                            console.log(`Found input: ${input.length}, Found errorDiv: ${errorDiv.length}`);
                            
                            // Add invalid class to input
                            if(input.length > 0){
                                input.addClass('is-invalid');
                                console.log(`Added is-invalid to input`);
                            } else {
                                // Fallback: try to find by name attribute pattern
                                const inputByName = row.find(`input[name*="][${field}]"]`);
                                if(inputByName.length > 0){
                                    inputByName.addClass('is-invalid');
                                    console.log(`Added is-invalid to input (fallback)`);
                                }
                            }
                            
                            // Show error message
                            if(errorDiv.length > 0){
                                const errorMessage = Array.isArray(value) ? value.join(', ') : value;
                                errorDiv.text(errorMessage);
                                errorDiv.css({
                                    'display': 'block',
                                    'width': '100%'
                                }); // Ensure it's visible with proper styling
                                console.log(`Set error message: ${errorMessage}`);
                            } else {
                                console.warn(`Error div not found for ${key}`);
                                // Fallback: try to find error div by class
                                const errorDivFallback = row.find(`.invalid-feedback`);
                                if(errorDivFallback.length > 0){
                                    const errorMessage = Array.isArray(value) ? value.join(', ') : value;
                                    errorDivFallback.text(errorMessage);
                                    errorDivFallback.css({
                                        'display': 'block',
                                        'width': '100%'
                                    });
                                    console.log(`Set error message (fallback): ${errorMessage}`);
                                }
                            }
                        } else {
                            console.warn(`Row index ${backendIndex} not found (total rows: ${allRows.length})`);
                        }
                    }
                }
            });
        }

        // Hook into form component error handling
        // Intercept AJAX errors after form component processes them
        $(document).ajaxComplete(function(event, xhr, settings){
            // Only handle errors from item form submission
            if(settings && settings.url && (settings.url.includes('item.store') || settings.url.includes('item.update'))){
                if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors){
                    // Wait a bit for form component to process errors first
                    setTimeout(function(){
                        parseDetailErrors(xhr.responseJSON.errors);
                    }, 150);
                }
            }
        });

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
    </script>
@endpush

