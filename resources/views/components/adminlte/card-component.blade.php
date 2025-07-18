<{{ $tag }} {{ $attributes->merge(['id' => $id, 'class' => 'card', ...($asForm ? ['method' => $method, 'enctype' => $enctype, 'autocomplete' => 'off'] : [])]) }}>
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
                <button type="reset" class="btn btn-outline-danger d-none">Reset</button>
                <button type="submit" class="btn btn-outline-success">{{ $button }}</button>
            </div>
        </div>
    @endif
</{{ $tag }}>