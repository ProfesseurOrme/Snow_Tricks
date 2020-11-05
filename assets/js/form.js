$(document).on("change", ".file-input-element", function () {

    let fileName = "";
    for(let i = 0; i < $(this).get(0).files.length; i++) {
        (i >= 1) ? fileName += " / " +  $(this).get(0).files[i].name.replace(/^C:\\fakepath\\/i, "") : fileName += $(this).get(0).files[i].name.replace(/^C:\\fakepath\\/i, "");
    }
    $(this).parent(".form-file").find(".input-file-value").text(fileName);
})

let collectionHolderPicture, collectionHolderVideo;
let addTagButtonVideo = $(".add_tag_link_video");
let newLinkVideo = $("<div class=\"add-video-block\"></div>");

$(document).ready(function() {

    collectionHolderVideo = $("div.add-video");
    collectionHolderVideo.append(newLinkVideo);
    collectionHolderVideo.data("index", collectionHolderVideo.find("input").length);

    if(collectionHolderVideo.find(":input").length < 1){
        addTagForm(collectionHolderVideo,newLinkVideo);
    }

    addTagButtonVideo.on("click", function() {
        addTagForm(collectionHolderVideo, newLinkVideo);
    });
});

function addTagForm(collectionHolder, newLink) {

    let prototype = collectionHolder.data("prototype");
    let index = collectionHolder.data("index");
    let newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    collectionHolder.data("index", index + 1);

    let newFormLine = $("<div class=\"add-video-block\"></div>").append(newForm);

    newLink.before(newFormLine);
}