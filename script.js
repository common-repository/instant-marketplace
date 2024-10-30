jQuery(document).ready(function ($) {
    if (typeof registerBtnText !== 'undefined' && typeof registerAction !== 'undefined' && typeof registerLaterBtnId !== 'undefined' && typeof pluginName !== 'undefined' ) {
        $('#' + registerBtnText).click(function () {

            var email_reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            $('#registration-form').find('.err').hide();
            var OK = true;
            if (!$('#tnc').is(':checked')) {
                $('#tnc').parent().find('.err').show();
                OK = false;
            }
            if (!email_reg.test($('#email').val())) {
                $('#email').parent().find('.err').show();
                OK = false;
            }
            /*			else if ($('#fullname').val() == '')
             {
             alert('Name could not be empty');
             }	*/
            if (OK) {
                var data = {
                    action: registerAction,
//					fullname: $('#fullname').val(),
                    email: $('#email').val()
                };
                /*				$.post(pixterAjax.ajaxurl, data, function(response)
                 {
                 alert('Got this from the server: ' + response);
                 });*/
                $.ajax({
                    type: 'POST',
                    url: pixterAjax.ajaxurl,
                    dataType: 'json',
                    cache: false,
                    data: data,
                    success: function (data) {
                        if (data.success) {
                            message = (typeof data.message == 'undefined') ? 'Thank you for registrating with us.' : data.message;
                            $('#register-pm').append('<div style="width:100%;height:100%;position:absolute;top:0;left:0;margin:0" class="wrap"><h2 style="padding:90px;color:#009;">' + message + '</h2></div>');
                            $('.p1xtr-reg-notice').hide();
                            setTimeout(function () {
                                $('#register-pm').hide();
                                $('.register-btn').hide();
                                $('#admin_page_class').show();
                            }, 3000);
                        }
                        else {
                            message = (typeof data.message == 'undefined') ? 'Unknown server error.' : data.message;
                            alert('Error:\n' + message);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(textStatus + '\n' + XMLHttpRequest.responseText);
                    }
                });
            }
        });

        $('#'+registerLaterBtnId).click(function (){
            $('#register-pm').hide();
            $('#admin_page_class').show();

        });
    }
});