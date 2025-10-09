<div class="small-box {{ $colors }}">
    <div class="inner">
        <h3>{{ $title }}</h3>
        <p>{{ $content }}</p>
    </div>
    @if($icon)
        <div class="icon">
            <i class="{{ $icon }}"></i>
        </div>
    @endif
    @if($link)
        <a href="{{ $link }}" class="small-box-footer" target="_blank">{{ $title }} <i class="fas fa-up-right-from-square"></i></a>
    @endif
</div>