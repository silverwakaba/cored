@extends('layouts.adminlte')
@section('title', Str::of($datas['title']))
@section('content')
    <!-- This view is for a standardized index to fill the blank pages from the groupped routes -->
    <x-Adminlte.ContentWrapperComponent :breadcrumb="$datas['breadcrumb']">
        <x-Adminlte.CardComponent>
            @if(isset($datas['navigation']))
                <div class="row row-cols-1 row-cols-lg-2">
                    @foreach($datas['navigation'] as $navigation)
                        <div class="col">
                            <x-Adminlte.BoxComponent :title="$navigation['title']" :content="(isset($navigation['content']) ? $navigation['content'] : null)"
                                :icon="(isset($navigation['icon']) ? $navigation['icon'] : null)" :link="(isset($navigation['link']) ? $navigation['link'] : null)" />
                        </div>
                    @endforeach
                </div>
            @else
                <p class="m-0">TBA.</p>
            @endif
        </x-Adminlte.CardComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection