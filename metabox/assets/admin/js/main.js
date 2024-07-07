var frame;
var gframe;
;(function ($) {
    $(document).ready(function () {
        var image_url = $('#mtb_img_url').val();

        if(image_url){
            $('#mtb_images_container').html(`<img class="img" src='${image_url}' >`)

        }
        var gellery_images_url = $('#mtb_imgs_url').val();
        gellery_images_url=gellery_images_url?gellery_images_url.split(';'):[];
        for(i in gellery_images_url){
            var images_url=gellery_images_url[i];
            $('#mtb_gellery_container').append(`<img style="margin-right: 30px;" src='${images_url}' >`)

        }




        $("#mtb_upload_img").on("click", function (e) {
            e.preventDefault();
            // If the media frame already exists, reopen it.
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media({
                title: 'Select or Upload Image',
                button: {
                    text: 'Insert Image'
                },
                multiple: false
            });
            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();
                // console.log(attachment);
                $('#mtb-image-id').val(attachment.id);
                $('#mtb_img_url').val(attachment.sizes.thumbnail.url);
                $('#mtb_images_container').html(`<img class="img" src='${attachment.sizes.thumbnail.url}' >`)
            });
            frame.open()
            // return false;
        });

        //gellery jquerry


        $("#mtb_upload_gellery").on("click", function (e) {
            e.preventDefault();
            // If the media frame already exists, reopen it.
            if (gframe) {
                gframe.open();
                return;
            }
            gframe = wp.media({
                title: 'Select or Upload Image',
                button: {
                    text: 'Insert Image'
                },
                multiple: true
            });
            gframe.on('select', function () {
                const image_ids=[];
                const image_urls=[];
                var attachments = gframe.state().get('selection').toJSON();
                $('#mtb_gellery_container').html(' ');
                for (i in attachments){
                    const attachment=attachments[i];
                    image_ids.push(attachment.id)
                    image_urls.push(attachment.sizes.thumbnail.url)
                    $('#mtb_gellery_container').append(`<img style="margin-right: 30px;" src='${attachment.sizes.thumbnail.url}' >`)
                }
                // console.log(image_ids,image_urls);
                $('#mtb_images_id').val(image_ids.join(";"));
                $('#mtb_imgs_url').val(image_urls.join(";"));
            });
            gframe.open()
            // return false;
        });
    });
})(jQuery);





