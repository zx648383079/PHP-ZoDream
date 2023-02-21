function bindLog() {
    let btn = $('a[data-type=import]');
    btn.upload({
        url: btn.data('url'),
        template: '{url}',
        filter: '.csv',
        onafter: function(data: any, element: JQuery) {
            parseAjax(data);
            return false;
        }
    });
    $('[name="edit"]').on('click',function() {
        $('#log-table').toggleClass('edit-mode', $(this).is(':checked'));
    });
}