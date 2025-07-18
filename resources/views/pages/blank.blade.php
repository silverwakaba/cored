@extends('layouts.adminlte')
@section('title', 'Blank')
@section('content')
    <x-Adminlte.ContentWrapperComponent title="Aru" :previous="route('fe.page.index1')">
        <x-Adminlte.CardComponent id="theForm" :asForm="false" title="ABC">

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#theModalModal">
                Launch the modal
            </button>

            <!-- Input -->
            <x-Form.InputForm name="theInput" type="text" text="The Input" :hidden="false" :required="true" />

            <!-- Select -->
            <x-Form.SelectForm name="theSelect" text="The Select" :hidden="false" :required="true" :multiple="true" />

        </x-Adminlte.CardComponent>

        <!-- Modal Disini -->
        <x-Adminlte.ModalComponent id="theModal" :asForm="true" title="DEF">
            <!--  -->
        </x-Adminlte.ModalComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Script Disini Bang
        // Oke gak nih
    </script>
@endpush