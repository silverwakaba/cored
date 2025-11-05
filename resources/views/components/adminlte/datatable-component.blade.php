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
                            <button id="btn-delete" class="dropdown-item" data-id="${ row.id }"><i class="fas fa-trash mr-2"></i>Delete</button>
                        </div>
                    </div>
                `;
            },
        },
    ],
});