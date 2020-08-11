$(document).ready(function() {

    $('#input-username').focusout(function () {
        if($(this).val() !== '' && $(this).val().length > 4) {

            const help = $('#usernameHelp');

            const url = $(this).attr('data-search');
            const data = $(this).val();

            const icoValid = $('<span class=\"form-control-feedback\"><i class=\"material-icons\">done</i></span>');
            const icoError = $('<span class=\"form-control-feedback\"><i class=\"material-icons\">clear</i></span>')


            if($('has-success')) {
                $(this).closest('div').removeClass('has-success');
                $('.form-control-feedback').remove();
            }
            if ($('has-danger')) {
                $(this).closest('div').removeClass('has-danger');
                $('.form-control-feedback').remove();
            }

            $.ajax({
                type: 'POST',
                url : url,
                dataType: 'json',
                data : {
                    username : data
                },
                success: json => {
                    help.text('Identifiant valide et disponible !');
                    icoValid.insertBefore(help);
                    $(this).parent().addClass('has-success');
                },
                error: () => {
                    help.text('Identifiant déja existant. Veuillez en choisir un autre !');
                    icoError.insertBefore(help);
                    $(this).parent().addClass('has-danger');
                }
            },
            )

        }
    });

});