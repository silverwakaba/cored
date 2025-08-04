<li class="nav-item">
    @foreach($menuItems as $item)
        @if(empty($item['parent_id']))
            <li class="nav-header">{{ $item['name'] }}</li>
            @if(!empty($item['children']))
                @foreach($item['children'] as $child)
                    <x-Adminlte.Dynamic-Menu-Item :item="$child" />
                @endforeach
            @endif
        @else
            <x-Adminlte.Dynamic-Menu-Item :item="$item" />
        @endif
    @endforeach
</li>