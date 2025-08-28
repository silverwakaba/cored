<div class="modal fade" id="{{ $id }}Modal" data-backdrop="static" data-keyboard="false" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <{{ $tag }} {{ $attributes->merge(['id' => $id, 'class' => 'modal-dialog modal-xl modal-dialog-scrollable', ...($asForm ? ['method' => $method, 'enctype' => $enctype, 'autocomplete' => 'off'] : [])]) }}>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="overlay-wrapper">
                    <div id="overlay" class="d-none">
                        <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                    </div>
                    {{ $slot }}
                </div>
            </div>
            @if($asForm)
                <div class="modal-footer">
                    <input type="hidden" name="_token" class="d-none" value="{{ csrf_token() }}" readonly>
                    <button id="buttonReset" type="reset" class="btn btn-danger">Reset</button>
                    <button id="buttonSubmit" type="submit" class="btn btn-success">{{ $button }}</button>
                </div>
            @endif
        </div>
    </{{ $tag }}>
</div>