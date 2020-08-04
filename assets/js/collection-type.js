let collectionHolderPicture, collectionHolderVideo;

let addTagButtonVideo = $('.add_tag_link_video');

//et newLinkPicture = $('<div class="add-picture-block"></div>');
let newLinkVideo = $('<div class="add-video-block"></div>');

$(document).ready(function() {
    //collectionHolderPicture = $('div.add-picture');
    collectionHolderVideo = $('div.add-video');

    //collectionHolderPicture.append(newLinkPicture);
    collectionHolderVideo.append(newLinkVideo);

    //collectionHolderPicture.data('index', collectionHolderPicture.find('input').length);
    collectionHolderVideo.data('index', collectionHolderVideo.find('input').length);

    /*if (collectionHolderPicture.find(':input').length < 1) {
        addTagForm(collectionHolderPicture,newLinkPicture);
    } */

    if(collectionHolderVideo.find(':input').length < 1){
        addTagForm(collectionHolderVideo,newLinkVideo);
    }

    /*addTagButtonPicture.on('click', function() {

        addTagForm(collectionHolderPicture, newLinkPicture);
    });*/

    addTagButtonVideo.on('click', function() {

        addTagForm(collectionHolderVideo, newLinkVideo);
    });

});

function addTagForm(collectionHolder, newLink) {

    let prototype = collectionHolder.data('prototype');
    let index = collectionHolder.data('index');
    let newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);
    collectionHolder.data('index', index + 1);
    /*let newFormLine;
    if(newLink.hasClass('add-picture-block')) {
        newFormLine = $('<div class="add-picture-block"></div>').append(newForm);
    } else {
        newFormLine = $('<div class="add-video-block"></div>').append(newForm);
    } */
    let newFormLine = $('<div class="add-video-block"></div>').append(newForm);

    newLink.before(newFormLine);
}