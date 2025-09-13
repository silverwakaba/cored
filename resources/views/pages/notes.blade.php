@extends('layouts.adminlte')
@section('title', 'Personal Notes')
@section('content')
    <x-Adminlte.ContentWrapperComponent>
        <x-Adminlte.CardComponent id="theForm" :asForm="false" :upsert="false">
            <p id="contentParagraph">Paragraph.</p>
        </x-Adminlte.CardComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Init jquery
        $(document).ready(function(){
            initReader();
        });

        // Init reader
        function initReader(){
            // Get route with id placeholder
            const readRouteBase = `{{ route('fe.apps.notes.read', ['id' => '::ID::']) }}`;

            // Change id placeholder with the actual id
            let readRoute = readRouteBase.replace('::ID::', {{ request()->id }});

            // Handle form populate
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: readRoute,
                success: function(response){
                    // Rename title
                    $('#contentTitle').text(response.data.title);

                    // Replace content
                    $('#contentParagraph').html(response.data.content.replace(/\n/g, '<br>'));
                },
                error: function(response){
                    // Error
                    Swal.fire({
                        icon: 'error',
                        text: response.responseJSON.message,
                        allowOutsideClick: () => {
                            return false;
                        },
                    }).then(() => {
                        // Redirect
                        window.location.href = "{{ route('fe.page.index') }}";
                    });
                },
            });
        }
    </script>
@endpush