@php
    $hasChildren = $item->authorizedChildren()->isNotEmpty();
    $isActive = $item->isActive();
    $isOpen = $item->hasActiveChild;
    $resolvedUrl = $item->getResolvedUrl();
@endphp

<li @class(["nav-item", "menu-open" => $isOpen])>
    <a href="{{ $hasChildren ? '#' : $resolvedUrl }}" @class(["nav-link", "active" => $isActive])>
        @if($item->icon)
            <i class="nav-icon {{ $item->icon }}"></i>
        @else
            <i class="nav-icon fas fa-circle"></i>
        @endif
        <p>
            {{ $item->title }}
            @if($hasChildren)
                <i class="right fas fa-angle-left"></i>
            @endif
        </p>
    </a>
    @if($hasChildren)
        <ul class="nav nav-treeview">
            @foreach($item->authorizedChildren() as $child)
                <x-Adminlte.SidebarNavigationItemComponent :item="$child" />
            @endforeach
        </ul>
    @endif
</li>