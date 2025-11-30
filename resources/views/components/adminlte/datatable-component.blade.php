// Datatable
$('#{{ $id }}').DataTable({
    ordering: false,
    processing: true,
    serverSide: true,
    searchDelay: 1500,
    ajax: {
        type: '{{ $method }}',
        data: function(d){
            // Pass parameter
            d.type = 'datatable';
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
                            <button id="btn-upsert" class="dropdown-item" data-id="${ row.id }"><i class="fas fa-pen-to-square mr-2"></i>Edit</button>
                            @if($deleteUrl)
                                <button id="btn-delete-{{ $id }}" class="dropdown-item" data-id="${ row.id }"><i class="fas fa-trash mr-2"></i>Delete</button>
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
            text: 'Are you sure? This action cannot be undone.',
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