<li class="nav-header">{{ $item->title }}</li>
@foreach($item->authorizedChildren() as $child)
    @if($child->is_header)
        <x-Adminlte.SidebarNavigationHeaderComponent :item="$item" />
    @else
        <x-Adminlte.SidebarNavigationItemComponent :item="$item" />
    @endif
@endforeach