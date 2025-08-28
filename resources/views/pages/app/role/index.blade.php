@extends('layouts.adminlte')
@section('title', 'Role')
@section('content')
    <x-Adminlte.ContentWrapperComponent>
        <x-Adminlte.CardComponent id="theForm" :asForm="false" :upsert="true" title="Manage Role">
            <x-Adminlte.TableComponent id="theTable" />
        </x-Adminlte.CardComponent>
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" title="Manage Role">
            <x-Form.InputForm name="name" type="text" text="Name" :required="true" />
        </x-Adminlte.ModalComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Init jquery
        $(document).ready(function(){
            // Server-side Datatable from API Endpoint
            $("#theTable").DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: {
                    type: "GET",
                    data: function(d){
                        // Pass parameter
                        d.type = 'datatable';

                        return d;
                    },
                    url: "{{ route('fe.apps.role.list') }}",
                },
                columns: [
                    {
                        title: 'No',
                        render: function(data, type, row, meta){
                            return `${ meta.row + meta.settings._iDisplayStart + 1 }`;
                        },
                    },
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
                    {
                        title: "Action",
                        render: function(data, type, row, meta){
                            return `
                                <button id="btn-upsert" class="btn btn-sm btn-block btn-danger" data-id="${ row.id }">Edit</button>
                            `;
                        },
                    },
                ],
            });

            // Handle overlay class for form processing state
            function setProcessingState(processing){
                const submit = $('#buttonSubmit');
                const overlay = $('#overlay');

                if(processing){
                    submit.prop('disabled', true);

                    overlay.addClass('overlay').removeClass('d-none');
                }
                else{
                    submit.prop('disabled', false);

                    overlay.addClass('d-none').removeClass('overlay');
                }
            }

            // Init upsert (Update or Insert)
            $('body').on('click', '#btn-upsert', function(){
                // Open modal
                $('#theModalModal').modal('show');

                // Reset the form
                $('#buttonReset').trigger('click');

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Get data id
                let dataID = $(this).data('id');

                // Handle insert
                if(!dataID){
                    // Rename modal title
                    $('#theModalLabel').text('Add Role');
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Edit Role');

                    // Get route with id placeholder
                    const readRouteBase = "{{ route('fe.apps.role.read', ['id' => '::ID::']) }}";

                    // Change id placeholder with the actual id
                    let readRoute = readRouteBase.replace('::ID::', dataID);

                    // Handle form populate
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: readRoute,
                        success: function(response){
                            // Manual populate dengan formatting untuk invoice_value
                            $('[name="name"]').val(response.data.name);
                        }
                    });

                    // Get route with id placeholder
                    const updateRouteBase = "{{ route('fe.apps.role.stp', ['id' => '::ID::']) }}";

                    // Change id placeholder with the actual id
                    let updateRoute = updateRouteBase.replace('::ID::', dataID);
                }
            });
        });
    </script>
@endpush