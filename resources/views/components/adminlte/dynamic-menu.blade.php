@php
    if(!function_exists('isChildRouteActive')){
        function isChildRouteActive($children){
            foreach($children as $child){
                if(isset($child['route']) && Route::currentRouteName() == $child['route']){
                    return true;
                }

                if(!empty($child['children']) && isChildRouteActive($child['children'])){
                    return true;
                }
            }
            
            return false;
        }
    }
@endphp

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    @foreach($menuItems as $item)
        @if(empty($item['parent_id']))
            @if($item['type'] == 'h')
                <li class="nav-header">{{ $item['name'] }}</li>
            @endif
            @if(!empty($item['children']))
                <li @class(["nav-item", "menu-open" => isChildRouteActive($item['children'])])>
                    @foreach($item['children'] as $child)
                        <x-Adminlte.Dynamic-Menu-Item :item="$child" />
                    @endforeach
                </li>
            @endif
        @else
            <x-Adminlte.Dynamic-Menu-Item :item="$item" />
        @endif
    @endforeach
</ul>