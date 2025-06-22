@extends('layouts.adminlte')
@section('title', 'Blank')
@section('content')
    <x-Adminlte.ContentWrapperComponent title="Aru" :previous="route('fe.page.index1')" breadcrumb="home">
        <x-Adminlte.CardComponent :asForm="true" title="ABC">
            ABC
        </x-Adminlte.CardComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Script Disini Bang
        // Oke gak nih
    </script>
@endpush