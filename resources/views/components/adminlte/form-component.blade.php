// Handle form input while clearing previous action to avoid double submit
$('#{{ $id }}').off('submit').on('submit', function(e){ // => id form
    // Stop any possible unexpected default action
    e.preventDefault();

    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    // By default when submitting the form, the state of form processing is set as true
    setProcessingState(true);

    // Populate form data into single variable
    let formData = new FormData(this);

    // Handle ajax
    $.ajax({
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        url: routeAction, // => yg jadi inti dari componentnya
        success: function(response){
            // Handle success
            if(response.success){
                // If success, form processing state is set as true to avoid duplication
                setProcessingState(true);

                // Success
                Swal.fire({
                    icon: 'success',
                    text: response.message || response.responseJSON.message || 'Something went wrong.',
                    allowOutsideClick: () => {
                        return false;
                    },
                }).then(() => { // => action kalau berhasil (biasanya = modal: reset/hide | form: redirect)
                    // Trigger reset button
                    $('#buttonResetModal').trigger('click');
                    
                    // Hide modal
                    $('#{{ $id }}Modal').modal('hide');

                    // Then reset the form processing state afterward
                    setProcessingState(false);
                });
            } else {
                // API error
                Swal.fire({
                    icon: 'error',
                    text: response.message || response.responseJSON.message || 'Something went wrong.',
                }).then(() => { // => kalo error sama
                    // Reset form processing state
                    setProcessingState(false);
                });
            }
        },
        error: function(response){
            // Refresh page if session/csrf_token expired
            if([200, 419].includes(response.status)){
                Swal.fire({
                    icon: 'warning',
                    text: response.message || response.responseJSON.message || 'Something went wrong.',
                    allowOutsideClick: () => {
                        return false;
                    },
                }).then(() => {
                    // Reload page
                    setTimeout(function(){
                        window.location.reload();
                    }, 0);
                });
            }
            
            // Show error message via Swal
            else if(![422].includes(response.status)){
                // Swal message
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    position: 'top-right',
                    text: response.message || response.responseJSON.message || 'Something went wrong.',
                    timer: 3000,
                    showConfirmButton: false,
                });
            }
            
            // Show error message via form feedback
            else{
                // If error, form processing state is set as false
                setProcessingState(false);

                // Handle error message
                let errors = response.responseJSON.errors;
                
                $.each(errors, function(key, value){
                    let input = $('[name="' + key + '"]');
                    let errorElement = $('#' + key + '-error');
                    
                    input.addClass('is-invalid');
                    errorElement.text(value[0]);
                });
            }
        }
    });
});