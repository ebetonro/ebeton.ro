/**
 * Created by Cristian on 5/6/2017.
 */
jQuery( document ).ready(function() {
    jQuery('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
});

function checkInputs() {
    jQuery(".form-control").each(function() {
      var element_to_check = $(this);
      element_to_check.on("input", function() {
          if(element_to_check.attr('check') == 'name'){
              checkNameAndValidate(element_to_check);
          }
          if(element_to_check.attr('check') == 'username'){
              checkUsernameAndValidate(element_to_check);
          }
          if(element_to_check.attr('check') == 'email'){
              checkEmailAndValidate(element_to_check);
          }
          if(element_to_check.attr('check') == 'password'){
              checkPassword(element_to_check);
          }

          if(element_to_check.attr('check') == 'password_retype'){
              confirmPasswordEqual(element_to_check);
          }
          //console.log(this.id + " sa schimbat in " + this.value);
      });
  });
}

function checkNameAndValidate(element) {
    if($.trim(element.val()).length == 0){
        changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- nu poate fi gol.');
    }else{
        if ($.trim(element.val()).length < 3) {
            changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- minim 3 caractere.');
        } else {
            if (/\S/.test(element.val())) {
                if($.trim(element.val()).length == element.val().length){
                    if(/\d/.test(element.val())){
                        changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- nu poate contine numere.');
                    } else {
                        changeHelpers(element, 'has-success', 'fa-check', element.attr('alt') + '- Ok');
                    }
                } else{
                    changeHelpers(element, 'has-error', 'fa-times-circle-o', 'Primul sau ultimul caracter este spatiu.');
                }
            } else {
                changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- nu poate fi spatiu.');
            }
        }
    }
}

function checkUsernameAndValidate(element) {
    if ($.trim(element.val()).length == 0) {
        changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- nu poate fi gol.');
    } else {
        if ($.trim(element.val()).length < 5) {
            changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- minim 5 caractere.');
        } else {
            if (/^([a-zA-Z0-9_-]+)$/.test(element.val())) {
                jQuery.ajax({
                    type: 'POST',
                    url: '_utils/ajax_checks.php',
                    data: 'action=checkUsernameExists&value=' + element.val() + '&field=username',
                    cache: false,
                    success: function (response) {
                        if (response == 1) {
                            changeHelpers(element, 'has-error', 'fa-times-circle-o', 'Acest utilizator nu este disponibil.');
                        } else {
                            changeHelpers(element, 'has-success', 'fa-check', element.attr('alt') + '- Ok');
                        }
                    }
                });
            } else{
                changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- invalid.');
            }
        }
    }
}

function checkEmailAndValidate(element) {
    if ($.trim(element.val()).length == 0) {
        changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- nu poate fi gol.');
    } else {
        if ($.trim(element.val()).length < 2) {
            changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- minim 5 caractere.');
        } else {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(re.test(element.val())){
                jQuery.ajax({
                    type: 'POST',
                    url: '../_utils/ajax_checks.php',
                    data: 'action=checkUsernameExists&value=' + element.val() + '&field=email',
                    cache: false,
                    success: function (response) {
                        if (response == 1) {
                            changeHelpers(element, 'has-error', 'fa-times-circle-o', 'Aceasta adresa nu este disponibila.');
                        } else {
                            changeHelpers(element, 'has-success', 'fa-check', element.attr('alt') + '- Ok');                        }
                    }
                });
            } else {
                changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- invalid.');
            }
        }
    }
}

function checkPassword(element){
    var score = 0;
    var good_pattern = new RegExp(/[~!#$=*%&\?+\-,./|]/); //unacceptable chars
    var wrong_pattern = new RegExp(/['\^\[\]\\';/{}\\":<>]/); //unacceptable chars

    if ($.trim(element.val()).length == 0) {
        changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- ai nevoie de o parola.');
    } else {
        if ($.trim(element.val()).length < 5) {
            changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- este prea scurta.');
        } else {
            if(element.val().match(/\d+/g) == null){
                changeHelpers(element, 'has-error', 'fa-times-circle-o', element.attr('alt') + '- trebuie sa contina un numar.');
            } else {
                if(wrong_pattern.test(element.val())){
                    changeHelpers(element, 'has-error', 'a-times-circle-o', 'Nu poate sa contina: \'\^\[\]\\;":<>');
                } else {
                    if(!good_pattern.test(element.val())){
                        changeHelpers(element, 'has-error', 'a-times-circle-o', 'Trebuie sa contina cel putin un caracter dintre: ~!#$=*%&+-?.,/|');
                    } else {
                        changeHelpers(element, 'has-success', 'fa-check', element.attr('alt') + '- Ok');
                    }
                }
            }

        }
    }
}


function confirmPasswordEqual(element) {
    var password_element = jQuery('#password');
    if (element.val() != password_element.val()) {
        changeHelpers(element, 'has-error', 'fa-times-circle-o', 'Nu este identic cu campul parola!');
    } else {
        changeHelpers(element, 'has-success', 'fa-check', element.attr('alt') + '- Ok');
    }
}

function changeHelpers(element, group_class, icon_class, label_text){
    var group = jQuery('#'+element.attr('id') + '_group');
    var label = jQuery('#'+element.attr('id') + '_label');
    group.removeClass();
    group.addClass('form-group has-feedback '+group_class);
    label.html('<i class="fa '+icon_class+'"></i> '+label_text);
}