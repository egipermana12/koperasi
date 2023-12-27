$('#formLogin').on('submit', function(e) {
    e.preventDefault();
    let data = new FormData(this);
    $.ajax({
        type: "POST",
        url: base_url + "login",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        dataType: "JSON",
        beforeSend: function() {
            $('#btnLogin').attr('disabled', 'disabled');
            $('#btnLoginn').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span class="visually-hidden">Loading...</span></div>&nbspProcessing');
        },
        complete: function() {
            $('#btnLogin').removeAttr('disabled');
            $('#btnLogin').html('Login');
        },
        success: function(res) {
            if(res.success === true){
                window.location.href = res.messages;
            }else{
                if (res.messages instanceof Object) {
                    $.each(res.messages, function(index, value) {
                        var key = $("#" + index);

                        key.closest('.form-control')
                        .removeClass('is-invalid')
                        .removeClass('is-valid')
                        .addClass(value.length > 0 ? 'is-invalid' : 'is-valid')
                        .siblings('.text-danger').remove();

                        key.after(value);

                        if (value.length > 0) {
                                // Set focus only if the value is invalid
                            key.focus();
                        }

                    });
                } else {
                    $('#username').closest('.form-control').removeClass('is-invalid').removeClass('is-valid').siblings('.text-danger').remove();
                    $('#password').closest('.form-control').removeClass('is-invalid').removeClass('is-valid').siblings('.text-danger').remove();
                    alert_error(res.messages);
                }
            }
        }
    });
});