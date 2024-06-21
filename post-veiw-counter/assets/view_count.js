jQuery(document).ready(function($) {
    if (typeof pvc_ajax !== 'undefined' && pvc_ajax.post_id) {
        $.post(pvc_ajax.ajax_url, {
            action: 'pvc_count_view',
            post_id: pvc_ajax.post_id
        });
    }
});
