<{{ $tag }} {{ $attributes->merge(['id' => $id, 'class' => 'card', ...($asForm ? ['method' => $method, 'enctype' => $enctype, 'autocomplete' => 'off'] : [])]) }}>
    @if($title || $upsert)
        <div class="card-header">
            @if($title)
                <h3 class="card-title">{{ $title }}</h3>
            @endif
            @if($upsert)
                <div class="card-tools">
                    @if($upsert)
                        <button id="btn-upsert" href="javascript:void(0)" class="btn btn-tool btn-secondary"><i class="fas fa-plus mr-2"></i>Create</button>
                    @endif
                </div>
            @endif
        </div>
    @endif
    <div class="card-body">
        <div class="overlay-wrapper">
            <div id="overlay-card" class="d-none">
                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
            </div>
            {{ $slot }}
        </div>
    </div>
    @if($asForm)
        <div class="card-footer text-right p-2">
            <input type="hidden" name="_token" class="d-none" value="{{ csrf_token() }}" readonly>
            <div class="button-group" role="group" aria-label="Button Group">
                <button id="buttonResetCard" type="reset" class="btn btn-outline-danger d-none">Reset</button>
                <button id="buttonSubmitCard" @class(["btn btn-outline-success", "h-captcha" => $withCaptcha ]) {{ $attributes->merge(['data-callback' => 'onSubmitVerify', ...($withCaptcha ? ['data-sitekey' => $sitekeyCaptcha] : [] )]) }}>{{ $button }}</button>
            </div>
        </div>
    @endif
    <script>
        // Verify and append the response
        function onSubmitVerify(token){
            // Add token as response to the form if the captcha is enabled
            @if($withCaptcha)
                $('#{{ $id }}').append(`<input type="hidden" name="h-captcha-response" value="${ token }">`);
            @endif
            
            // Trigger form submission
            $('#{{ $id }}').submit();
        }
    </script>
</{{ $tag }}>