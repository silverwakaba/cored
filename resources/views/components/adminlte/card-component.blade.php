<{{ $tag }} {{ $attributes->merge(['id' => $id, 'class' => 'card', ...($asForm ? ['method' => $method, 'enctype' => $enctype, 'autocomplete' => 'off'] : [])]) }}>
    <div class="card-header">
        @if($title)
            <h3 class="card-title">{{ $title }}</h3>
        @endif
        @if($upsert)
            <div class="card-tools">
                <button id="btn-upsert" href="javascript:void(0)" class="btn btn-tool btn-secondary"><i class="fas fa-plus"></i> Create</button>
            </div>
        @endif
    </div>
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