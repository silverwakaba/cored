// Handle form input while clearing previous action to avoid double submit
$('#{{ $id }}').off('submit').on('submit', function(e){
    // Stop any possible unexpected default action
    e.preventDefault();

    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    // By default when submitting the form, the state of form processing is set as true
    setProcessingState(true);

    // Populate form data into single variable
    let formData = new FormData(this);

    // Use routeMethod if set, otherwise default to POST
    // routeMethod can be: 'POST', 'PUT', 'PATCH', 'DELETE'
    let httpMethod = (typeof routeMethod !== 'undefined' && routeMethod) ? routeMethod : 'POST';
    
    // Add method spoofing for PUT, PATCH, DELETE (Laravel requirement)
    if(['PUT', 'PATCH', 'DELETE'].includes(httpMethod)){
        formData.append('_method', httpMethod);
    }

    // Handle ajax
    $.ajax({
        type: 'POST', // Laravel method spoofing always uses POST
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        url: routeAction,
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
                }).then(() => {
                    @if($asModal)
                        // Trigger reset button
                        $('#buttonResetModal').trigger('click');
                        
                        // Hide modal
                        $('#{{ $id }}Modal').modal('hide');
                    @endif

                    @if($table)
                        // Reload table
                        $('#{{ $table }}').DataTable().ajax.reload(null, false);
                    @endif

                    @if($redirect)
                        // Redirect - use intended URL from response if available, otherwise use default redirect
                        let redirectUrl = response.redirect_url || "{{ $redirect }}";
                        window.location.href = redirectUrl;
                    @endif

                    @if($isReset)
                        // Reset the form processing state after all cleanup
                        setProcessingState(false);
                    @endif
                });
            } else {
                // API error
                Swal.fire({
                    icon: 'error',
                    text: response.message || response.responseJSON.message || 'Something went wrong.',
                }).then(() => {
                    // Reset form processing state
                    setProcessingState(false);
                });
            }
        },
        error: function(response){
            // Refresh page if session/csrf_token expired
            if([200, 302, 419].includes(response.status)){
                Swal.fire({
                    icon: 'error',
                    text: 'Fatal error.',
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
            else{
                // Swal message
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    position: 'top-right',
                    text: response.message || response.responseJSON.message || 'Something went wrong.',
                    timer: 3000,
                    showConfirmButton: false,
                });

                // Handle error message
                let errors = response.responseJSON.errors;
                
                $.each(errors, function(key, value){
                    // Try to find input with original key first (for single value)
                    let input = $('[name="' + key + '"]');
                    
                    // If not found, try with [] suffix (for multiple value)
                    if(input.length === 0){
                        input = $('[name="' + key + '[]"]');
                    }
                    
                    // Try to find error element with original key first
                    let errorElement = $('#' + key + '-error');
                    
                    // If not found, try with [] suffix (for multiple value)
                    // Use attribute selector to handle [] characters in ID
                    if(errorElement.length === 0){
                        errorElement = $('[id="' + key + '[]-error"]');
                    }
                    
                    // Apply error styling and message if elements found
                    if(input.length > 0){
                        input.addClass('is-invalid');
                    }
                    
                    if(errorElement.length > 0){
                        // Handle array of error messages (for multiple validation errors)
                        let errorMessage = Array.isArray(value) ? value.join(', ') : value;
                        errorElement.text(errorMessage);
                    }
                });

                // If error, form processing state is set as false
                setProcessingState(false);
            }
        }
    });
});