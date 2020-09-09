$(document).on('click', '.modal-delete', function () {
    const path = $(this).attr('data-path');
    const name = $(this).attr('data-trickname');
    $('#form-delete').attr('action',path);
    $('.modal-body p').text(Translator.trans('Modal_Delete_Confirmation') + ' : ' + name + ' ?');
})