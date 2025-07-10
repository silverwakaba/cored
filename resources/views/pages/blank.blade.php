@extends('layouts.adminlte')
@section('title', 'Blank')
@section('content')
    <x-Adminlte.ContentWrapperComponent title="Aru" :previous="route('fe.page.index1')">
        <x-Adminlte.CardComponent :asForm="true" title="ABC">
            <!-- Input -->
            <div id="input-div" class="form-group">
                <label for="input" class="form-label">Label</label>
                <input id="input" name="input" type="text" class="form-control is-invalid">
                <div id="input-error" class="invalid-feedback">Feedback</div>
            </div>

            <!-- Input Files -->
            <div id="input-div" class="form-group">
                <label for="input" class="form-label">Label</label>
                <input id="input" name="input" type="file" class="form-control-file is-invalid">
                <div id="input-error" class="invalid-feedback">Feedback</div>
            </div>

            <!-- Textarea -->
            <div id="input-div" class="form-group">
                <label for="input" class="form-label">Label</label>
                <textarea id="input" name="input" class="form-control is-invalid"></textarea>
                <div id="input-error" class="invalid-feedback">Feedback</div>
            </div>

            <!-- Select2 Normal -->
            <div id="input-div" class="form-group">
                <label for="input" class="form-label">Label</label>
                <select id="input" name="input" data-placeholder="Select an option..." data-allow-clear="1" class="form-control is-invalid select2bs4">
                    <option value="">Select an Option</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <div id="input-error" class="invalid-feedback">Feedback</div>
            </div>

            <!-- Select2 Multiple -->
            <div id="input-div" class="form-group">
                <label for="input" class="form-label">Label</label>
                <select id="input" name="input" multiple="multiple" data-placeholder="Select an option..." data-allow-clear="1" class="form-control is-invalid select2bs4">
                    <option value="">Select an Option</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <div id="input-error" class="invalid-feedback">Feedback</div>
            </div>
        </x-Adminlte.CardComponent>
    </x-Adminlte.ContentWrapperComponent>
@endsection
@push('script')
    <script>
        // Script Disini Bang
        // Oke gak nih
    </script>
@endpush