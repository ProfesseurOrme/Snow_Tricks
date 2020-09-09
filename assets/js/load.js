$(document).ready(function() {

    let btnLoad = $('#load-more');
    let dataLoaded = btnLoad.attr('data-loaded');

    let dataLoadedByDefault;
    let page = 2;

    btnLoad.on('click', function() {

        const url = $(this).attr('data-url');

        dataLoadedByDefault = $(this).attr('data-elements');

        $.ajax({
            type: 'POST',
            url : url,
            dataType: 'json',
            success: data => {

                data.forEach(element => {
                    $('.row-cols-md-4').append(cardTrick(element.id,element.name,element.slug,element.picture));
                    dataLoaded++;
                });
                page++;

                if(dataLoaded >= dataLoadedByDefault) {
                    $('.row-more').remove();
                } else {
                    $(this).attr('data-url',Routing.generate('home_load_more',{'page' : page}));
                }
            },
        },
        )
    })

    function cardTrick(id,name,slug,picture) {
        return "<div class=\"col-tricks col-md-6 col-lg-3 mx-auto card-columns mx-auto d-flex" +
            " justify-content-center\">" +
            "<div class=\"card\">" +
            "<img class=\"card-img-top\" src=\"/upload_directory/" + picture + "\"" +
            " rel=\"nofollow\" alt=\"Card image cap\">" +
            "<div class=\"card-body d-flex flex-row justify-content-between\">" +
            "<a href=\"" + Routing.generate('trick_detail', {slug: slug}) + "\"" +
            " data-toggle=\"tooltip\"" +
            " title=\"" + Translator.trans('Home_Link_Trick') + ' : ' + name + "\"><h4" +
            " class=\"card-title d-flex flex-column m-2\">" + name + "</h4></a>" +
            "<div class=\"home-tool\">" +
            "<a href=\"" + Routing.generate('trick_edit', {slug: slug}) + "\"" +
            " target=\"_blank\"" +
            " data-toggle=\"tooltip\" data-placement=\"top\" title=\"" + Translator.trans('Edit_Icon') + "\"><span" +
            " class=\"material-icons\">create</span></a><a class=\"modal-delete\" data-placement=\"top\"" +
        " data-toggle=\"modal\"" +
            " data-target=\"#deleteModal\" data-trickname=\"" + name + "\" data-path=\"" + Routing.generate('trick_delete', {slug : slug}) +"\"><span" +
            " data-toggle=\"tooltip\"" +
                " title=\"" + Translator.trans('Delete_Icon') + "\" data-placement=\"top\"" +
                " class=\"material-icons\">delete</span></a>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";
    }

    function cardComment() {

    }
});