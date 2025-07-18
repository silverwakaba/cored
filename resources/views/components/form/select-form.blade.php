<div id="{{ $name }}-div" class="form-group">
    <label for="{{ $name }}" @class(["form-label", "form-required" => $required])>{{ $text }}</label>
    <select id="{{ $name }}" name="{{ $name }}" data-placeholder="Select an option..." data-allow-clear="1" class="form-control select2bs4" {{ $attributes->merge(['multiple' => $multiple]) }}>
        <option value="">Select an Option</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
    </select>
    <div id="{{ $name }}-error" class="invalid-feedback">Feedback</div>
</div>