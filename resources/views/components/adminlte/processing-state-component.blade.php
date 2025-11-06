function setProcessingState(processing){
    // Prop
    let reset = $('#{{ $reset }}');
    let submit = $('#{{ $submit }}');
    let overlay = $('#{{ $overlay }}');

    // Process
    if(processing){
        reset.prop('disabled', true);

        submit.prop('disabled', true);

        overlay.addClass('overlay').removeClass('d-none');
    } else {
        reset.prop('disabled', false);

        submit.prop('disabled', false);

        overlay.addClass('d-none').removeClass('overlay');
    }
}