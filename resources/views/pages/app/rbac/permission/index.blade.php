@extends('layouts.adminlte')
@section('title', 'Permission')
@section('content')
    <x-Adminlte.ContentWrapperComponent breadcrumb="apps.rbac.permission">
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

        // Init datatable
        function initDatatable(){
            <x-Adminlte.DatatableComponent id="theTable" :tableUrl="route('fe.apps.rbac.permission.list')" :deleteUrl="route('fe.apps.rbac.permission.delete', ['id' => '::ID::'])" method="GET">
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

        // Init upsert => Gak bisa dibuat jadi component
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
                    routeAction = `{{ route('fe.apps.rbac.permission.create') }}`;

                    // Init form action
                    formAction(routeAction);
                }

                // Handle update
                else{
                    // Rename modal title
                    $('#theModalLabel').text('Edit Permission');

                    // Get route with id placeholder
                    const readRouteBase = `{{ route('fe.apps.rbac.permission.read', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    let readRoute = readRouteBase.replace('::ID::', dataID);

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
                    const routeBase = `{{ route('fe.apps.rbac.permission.update', ['id' => '::ID::']) }}`;

                    // Change id placeholder with the actual id
                    routeAction = routeBase.replace('::ID::', dataID);

                    // Init form action
                    formAction(routeAction);
                }
            });
        }

        // Form action => Bisa jadi component asal masuk didalem function "initUpsert"
        function formAction(route){
            // Handle form input while clearing previous action to avoid double submit
            $('#theModal').off('submit').on('submit', function(e){ // => id form
                // Stop any possible unexpected default action
                e.preventDefault();

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // By default when submitting the form, the state of form processing is set as true
                setProcessingState(true);

                // Populate form data into single variable
                let formData = new FormData(this);

                // Handle ajax
                $.ajax({
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    url: route, // => yg jadi inti dari componentnya
                    success: function(response){
                        // Handle success
                        if(response.success){
                            // If success, form processing state is set as true to avoid duplication
                            setProcessingState(true);

                            // Success
                            Swal.fire({
                                icon: 'success',
                                text: response.message || response.responseJSON.message || 'Something went wrong.',
                                allowOutsideClick: () => {
                                    return false;
                                },
                            }).then(() => { // => action kalau berhasil (biasanya = modal: reset/hide | form: redirect)
                                // Trigger reset button
                                $('#buttonResetModal').trigger('click');
                                
                                // Hide modal
                                $('#theModalModal').modal('hide');

                                // Then reset the form processing state afterward
                                setProcessingState(false);
                            });
                        } else {
                            // API error
                            Swal.fire({
                                icon: 'error',
                                text: response.message || response.responseJSON.message || 'Something went wrong.',
                            }).then(() => { // => kalo error sama
                                // Reset form processing state
                                setProcessingState(false);
                            });
                        }
                    },
                    error: function(response){
                        // Refresh page if session/csrf_token expired
                        if([200, 419].includes(response.status)){
                            Swal.fire({
                                icon: 'warning',
                                text: response.message || response.responseJSON.message || 'Something went wrong.',
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
                        
                        // Show error message via Swal
                        else if(![422].includes(response.status)){
                            // Swal message
                            Swal.fire({
                                toast: true,
                                icon: 'error',
                                position: 'top-right',
                                text: response.message || response.responseJSON.message || 'Something went wrong.',
                                timer: 3000,
                                showConfirmButton: false,
                            });
                        }
                        
                        // Show error message via form feedback
                        else{
                            // If error, form processing state is set as false
                            setProcessingState(false);

                            // Handle error message
                            let errors = response.responseJSON.errors;
                            
                            $.each(errors, function(key, value){
                                let input = $('[name="' + key + '"]');
                                let errorElement = $('#' + key + '-error');
                                
                                input.addClass('is-invalid');
                                errorElement.text(value[0]);
                            });
                        }
                    }
                });
            });
        }

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
    </script>
@endpush