<div id="{{ $name }}-div" class="form-group">
    <label for="{{ $name }}" @class(["form-label", "form-required" => $required])>{{ $text }}</label>
    <select id="{{ $name }}" name="{{ $name }}" class="form-control select2bs4" {{ $attributes->merge(['multiple' => $multiple]) }}>
        <option value="">Select an Option...</option>
    </select>
    <div id="{{ $name }}-error" class="invalid-feedback">Feedback</div>
</div>