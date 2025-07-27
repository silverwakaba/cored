<div id="{{ $name }}-div" @class(["form-group"])>
    <div class="icheck-primary">
        <input id="{{ $name }}" name="{{ $name }}" type="checkbox" value="{{ $value }}" class="form-check-input">
        <label for="{{ $name }}" @class(["form-check-label", "form-required" => $required]) >{{ $slot }}</label>
        <div id="{{ $name }}-error" class="invalid-feedback"></div>
    </div>
</div>