@extends('layouts.adminlte')
@section('title', $datas['title'])
@section('content')
    <!-- This view is for a standardized index to fill the blank from the groupped routes -->
    <x-Adminlte.ContentWrapperComponent :breadcrumb="$datas['breadcrumb']">
        <x-Adminlte.CardComponent>
            Standardized
        </x-Adminlte.CardComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection