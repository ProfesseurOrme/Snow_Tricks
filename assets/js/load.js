$(document).ready(function() {

    let btnLoad = $("#load-more");
    let dataLoaded = btnLoad.attr("data-loaded");

    let dataLoadedByDefault;
    let page = 2;

    btnLoad.on("click", function() {

        const url = $(this).attr("data-url");

        dataLoadedByDefault = $(this).attr("data-elements");

        $.ajax({
            type: "POST",
            url : url,
            dataType: "html",
            success: (data) => {
                $("#add-elt").append(data);

                dataLoaded = $(".col-elt").length;

                page++;
                if ($(".chevron-bot").length && dataLoaded >= 13){
                    $(".chevron-bot").show();
                }

                if(dataLoaded >= dataLoadedByDefault) {
                    $(".row-more").remove();
                } else {
                    if (typeof $(this).data("slug") !== "undefined") {
                        $(this).attr("data-url",Routing.generate($(this).data("route"),{"slug" : $(this).data("slug"),"page" : page}));
                    } else {
                        $(this).attr("data-url",Routing.generate($(this).data("route"),{"page" : page}));
                    }
                }
            },
        },
        )
    })
});