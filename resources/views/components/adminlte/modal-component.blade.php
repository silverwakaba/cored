<div class="modal fade" id="{{ $id }}Modal" data-backdrop="static" data-keyboard="false" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <{{ $tag }} {{ $attributes->merge(['id' => $id, 'class' => 'modal-dialog modal-dialog-scrollable modal-xl', ...($asForm ? ['method' => $method, 'enctype' => $enctype, 'autocomplete' => 'off'] : [])]) }}>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="overlay-wrapper">
                    <div id="overlay-modal" class="d-none">
                        <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                    </div>
                    {{ $slot }}
                </div>
            </div>
            @if($asForm)
                <div class="modal-footer">
                    <input type="hidden" name="_token" class="d-none" value="{{ csrf_token() }}" readonly>
                    <button id="buttonResetModal" type="reset" class="btn btn-danger d-none">Reset</button>
                    <button id="buttonSubmitModal" type="submit" @class(["btn btn-outline-success", "h-captcha" => $withCaptcha ]) {{ $attributes->merge(['data-callback' => 'onSubmitVerify', ...($withCaptcha ? ['data-sitekey' => $sitekeyCaptcha] : [] )]) }}>{{ $button }}</button>
                </div>
            @endif
        </div>
        <script>
            // Verify and append the response
            function onSubmitVerify(token){
                // Add token as response to the form if the captcha is enabled
                @if($withCaptcha)
                    $('#{{ $id }}Modal').append(`<input type="hidden" name="h-captcha-response" value="${ token }">`);
                @endif
                
                // Trigger form submission
                $('#{{ $id }}').submit();
            }
        </script>
    </{{ $tag }}>
</div>