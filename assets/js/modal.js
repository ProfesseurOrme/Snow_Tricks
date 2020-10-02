$(document).on('click', '.modal-delete-trick', function () {
    $('#form-delete').attr('href',$(this).attr('data-path'));
    $('.modal-body p').text(Translator.trans('Modal_Delete_Trick_Confirmation') + ' : ' + $(this).attr('data-deleted-elt') + ' ?');
})

$(document).on('click', '.modal-delete-user', function () {
    $('#form-delete').attr('href',$(this).attr('data-path'));
    $('.modal-body p').text(Translator.trans('Modal_Delete_User_Confirmation') + ' : ' + $(this).attr('data-deleted-elt') + ' ?');
})

$(document).on('click', '.modal-delete-comment', function () {
    $('#form-delete').attr('href',$(this).attr('data-path'));
    $('.modal-body p').text(Translator.trans('Modal_Delete_Comment_Confirmation') + ' ?');
})