// Datatable
$('#{{ $id }}').DataTable({
    ordering: false,
    searching: {{ $searchable }},
    processing: true,
    serverSide: true,
    searchDelay: {{ $debounce }},
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

                if(filterValue && filterValue.trim() !== ''){
                    d[filterName] = filterValue.trim();
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
                            Swal.fire({
                                icon: 'success',
                                text: response.message || response.responseJSON.message || 'Something went wrong.',
                                allowOutsideClick: () => {
                                    return false;
                                },
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