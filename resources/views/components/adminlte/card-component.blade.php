<{{ $tag }} {{ $attributes->merge(['id' => $id, 'class' => 'card', ...($asForm ? ['method' => $method, 'enctype' => $enctype, 'autocomplete' => 'off'] : [])]) }}>
    @if($title || $upsert)
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
    @endif
    <div class="card-body">
        <div class="overlay-wrapper">
            <div id="overlay" class="d-none">
                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
            </div>
            {{ $slot }}
        </div>
    </div>
    @if($asForm)
        <div class="card-footer text-right p-2">
            <input type="hidden" name="_token" class="d-none" value="{{ csrf_token() }}" readonly>
            <div class="button-group" role="group" aria-label="Button Group">
                <button id="resetButton" type="reset" class="btn btn-outline-danger d-none">Reset</button>
                <button id="submitButton" @class(["btn btn-outline-success", "h-captcha" => $withCaptcha ]) {{ $attributes->merge(['data-callback' => 'onCaptchaVerified', ...($withCaptcha ? ['data-sitekey' => $sitekeyCaptcha] : [] )]) }}>{{ $button }}</button>
            </div>
        </div>
    @endif
</{{ $tag }}>
<script>
    // Verify and append the response
    function onCaptchaVerified(token){
        // Add token to the form
        @if($withCaptcha)
            $('#{{ $id }}').append(`<input type="hidden" name="h-captcha-response" value="${ token }">`);
        @endif
        
        // Trigger form submission
        $('#{{ $id }}').submit();
    }
</script>