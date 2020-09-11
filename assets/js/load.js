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
            dataType: 'html',
            success: data => {
                $('.row-cols-md-4').append(data);

                dataLoaded = $('.col-tricks').length;

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
});