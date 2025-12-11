<div id="{{ $name }}-div" @class(["form-group", "d-none" => $hidden])>
    <label for="{{ $name }}" @class(["form-label", "form-required" => $required])>{{ $text }}</label>
    @if($asTextarea)
        <textarea id="{{ $name }}" name="{{ $name }}" class="form-control"></textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" @class(['form-control' => $type !== 'file', 'form-control-file' => $type === 'file']) {{ $attributes->merge(['multiple' => $multiple]) }}>
    @endif
    <div id="{{ $name }}-error" class="invalid-feedback"></div>
</div>