@props(['item'])

@php
    $hasChildren = !empty($item['children']);
@endphp

<li @class(["nav-item", "menu-open" => $hasChildren && isChildRouteActive($item['children'])])>
    <a href="{{ $hasChildren ? '#' : (Route::has($item['route']) ? route($item['route']) : '#') }}" @class(["nav-link", "active" => (isChildRouteActive($item['children']) || Route::currentRouteName() == $item['route'])])>
        @if(!empty($item['icon']))
            <i class="nav-icon {{ $item['icon'] }}"></i>
        @endif
        <p>
            {{ $item['name'] }}
            @if($hasChildren)
                <i class="right fas fa-angle-left"></i>
            @endif
        </p>
    </a>  
    @if($hasChildren)
        <ul class="nav nav-treeview">
            @foreach($item['children'] as $child)
                <x-Adminlte.Dynamic-Menu-Item :item="$child" />
            @endforeach
        </ul>
    @endif
</li>