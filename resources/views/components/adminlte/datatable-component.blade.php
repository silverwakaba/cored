// Datatable
$('#{{ $id }}').DataTable({
    ordering: false,
    searching: {{ $searchable }},
    processing: true,
    serverSide: true,
    searchDelay: {{ $debounce }},
    lengthChange: true,
    lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
    dom: '<"row"<"col-sm-12 col-md-6 mb-md-3"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
    buttons: [
        {
            text: '<i class="fas fa-sync-alt"></i>',
            className: 'btn btn-sm btn-primary',
            action: function(e, dt, node, config){
                dt.ajax.reload(null, false);
            }
        },
        @if($upsert)
            {
                text: '<i class="fas fa-plus"></i>',
                className: 'btn btn-sm btn-secondary',
                attr: {
                    id: 'btn-upsert',
                    href: 'javascript:void(0)',
                }
            },
        @endif
    ],
    ajax: {
        type: '{{ $method }}',
        data: function(d){
            // Pass parameter type
            d.type = 'datatable';
            
            // Pass all filter parameters (filter, filter-name, filter-role, filter2, etc.)
            // Selector will match the input and the select with name starts with "filter" prefix
            $('input[name^="filter"], select[name^="filter"]').each(function(){
                let filterName = $(this).attr('name');
                let filterValue = $(this).val();

                // Handle array (from multiple select) and string (from single select/input)
                if(Array.isArray(filterValue)){
                    // For multiple select, check if array has at least one non-empty value
                    let hasValue = filterValue.some(function(val){
                        return val && val.toString().trim() !== '';
                    });
                    
                    if(hasValue){
                        d[filterName] = filterValue;
                    }
                } else if(filterValue && filterValue.toString().trim() !== ''){
                    // For single select/input, trim and check if not empty
                    d[filterName] = filterValue.toString().trim();
                }
            });
        },
        url: '{{ $tableUrl }}',
        error: function(response){
            // API error
            Swal.fire({
                icon: 'warning',
                text: response.message || response.responseJSON.message || 'Something went wrong.',
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
            title: 'Active', width: '5%', class: 'text-center',
            visible: false,
            render: function(data, type, row, meta){
                // Determine active status based on available columns
                let isActive = null;
                
                // If both columns exist, combine the logic: active if is_active is true AND deleted_at is null
                if(row.is_active !== undefined && row.deleted_at !== undefined){
                    isActive = (row.is_active == true && row.deleted_at == null);
                }
                // If only is_active exists, use it
                else if(row.is_active !== undefined){
                    isActive = (row.is_active == true);
                }
                // If only deleted_at exists, use it (null = active, not null = inactive)
                else if(row.deleted_at !== undefined){
                    isActive = (row.deleted_at == null);
                }
                // If neither exists, return empty
                else{
                    return '';
                }
                
                // Return icon based on active status
                return `<i class="fas fa-circle ${ isActive ? 'text-success' : 'text-danger' }"></i>`;
            },
        },
        {{ $slot }}
        {
            title: 'Action', width: '10%', class: 'text-center',
            render: function(data, type, row, meta){
                return `
                    <div class="btn-group btn-block" role="group">
                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                        <div class="dropdown-menu btn-block">
                            @if($editable)
                                <button id="btn-upsert" class="dropdown-item" data-id="${ row.id }"><i class="fas fa-pen-to-square mr-2"></i>Edit</button>
                            @endif
                            
                            @if($deleteUrl)
                                <button id="btn-delete-{{ $id }}" class="dropdown-item" data-id="${ row.id }">
                                    <i class="fas ${ (row.is_active !== undefined && row.is_active == false) || (row.deleted_at !== undefined && row.deleted_at !== null) ? 'fa-history' : 'fa-trash' } mr-2"></i> ${ row.is_active == true ? 'Deactivate' : row.is_active == false ? 'Activate' : 'Delete' }
                                </button>
                            @endif
                        </div>
                    </div>
                `;
            },
        },
    ],
    initComplete: function(settings, json){
        // Check if data has is_active or deleted_at column
        let hasActiveColumn = false;
        
        if(json && json.data && json.data.length > 0){
            // Check all rows for is_active or deleted_at (check up to first 10 rows for performance)
            let rowsToCheck = Math.min(json.data.length, 10);
            for(let i = 0; i < rowsToCheck; i++){
                let row = json.data[i];
                if(row && (row.hasOwnProperty('is_active') || row.hasOwnProperty('deleted_at'))){
                    hasActiveColumn = true;
                    break;
                }
            }
        }
        
        // Show/hide Active column (index 1, after No. column)
        let table = $('#{{ $id }}').DataTable();
        table.column(1).visible(hasActiveColumn);
    },
    drawCallback: function(settings){
        // Re-check column visibility on each draw (for reload/filter scenarios)
        let table = $('#{{ $id }}').DataTable();
        let api = this.api();
        let hasActiveColumn = false;
        
        // Check first few rows from the current page
        let rows = api.rows({page: 'current'}).data();
        let rowsToCheck = Math.min(rows.length, 10);
        
        for(let i = 0; i < rowsToCheck; i++){
            let row = rows[i];
            if(row && (row.hasOwnProperty('is_active') || row.hasOwnProperty('deleted_at'))){
                hasActiveColumn = true;
                break;
            }
        }
        
        // Update column visibility
        table.column(1).visible(hasActiveColumn);
    },
});

@if($deleteUrl)
    // Delete function
    $('body').on('click', '#btn-delete-{{ $id }}', function(){
        // Get data id
        let dataID = $(this).data('id');

        // Show confirmation
        Swal.fire({
            icon: 'warning',
            text: 'Are you sure? This action may not be reversible.',
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
                let deleteRouteBase = '{{ $deleteUrl }}';

                // Change id placeholder with the actual id
                routeDelete = deleteRouteBase.replace('::ID::', dataID);

                // Handle ajax
                $.ajax({
                    type: 'POST', // Laravel method spoofing requires POST
                    url: routeDelete,
                    dataType: 'json',
                    data: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response){
                        // Handle success
                        if(response.success){
                            // Success
                            let swalPromise = Swal.fire({
                                icon: 'success',
                                text: response.message || response.responseJSON.message || 'Something went wrong.',
                                allowOutsideClick: () => {
                                    return false;
                                },
                            });
                            
                            @if($reloadable)
                                swalPromise.then(() => {
                                    // Reload table
                                    $('#{{ $id }}').DataTable().ajax.reload(null, false);
                                });
                            @endif
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
                        Swal.fire({
                            icon: 'warning',
                            text: 'Something went wrong.',
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
                });
            }
        });
    });
@endif

@if($filterable)
    // Init filter for datatable
    (function(){
        // Handle filter with debounce
        let filterTimeout;
        
        // Debounce function to reload datatable
        function debounceReload(){
            clearTimeout(filterTimeout);

            filterTimeout = setTimeout(function(){
                $('#{{ $id }}').DataTable().ajax.reload(null, false);
            }, {{ $debounce }});
        }
        
        // Listen to all filter fields (input and select) that name starts with 'filter'
        // Use event delegation to handle dynamically added inputs
        $(document).on('keyup', 'input[name^="filter"]', debounceReload); // Suitable for input form
        $(document).on('change', 'select[name^="filter"]', debounceReload); // Suitable for select form
    })();
@endif