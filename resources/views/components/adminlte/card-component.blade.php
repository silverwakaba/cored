<{{ $tag }} @if($asForm) id="{{ $id }}" method="{{ $method }}" enctype="{{ $enctype }}" autocomplete="off" @endif {{ $attributes->merge(['class' => 'card']) }}>
    @if($title)
        <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
    @if($asForm)
        <div class="card-footer text-right p-2">
            <input type="hidden" name="_token" class="d-none" value="{{ csrf_token() }}" readonly />
            <div class="button-group" role="group" aria-label="Button Group">
                <button type="submit" class="btn btn-outline-success">{{ $button }}</button>
            </div>
        </div>
    @endif
</{{ $tag }}>