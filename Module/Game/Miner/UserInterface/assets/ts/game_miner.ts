
function bindWork() {
    let dialog = $('.area-dialog').dialog();
    let url = '';
    $('a[data-type="work"]').click(function(e) {
        e.preventDefault();
        url = $(this).attr('href');
        dialog.show();
    });
    dialog.box.on('click', '.area-box a', function() {
        let area_id = $(this).closest('.item').data('id');
        dialog.close();
        postJson(url, {
            area_id
        }, res => {
            parseAjax(res);
        });
    });
}