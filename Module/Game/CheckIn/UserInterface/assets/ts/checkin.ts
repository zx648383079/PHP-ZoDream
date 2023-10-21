function bindCheckIn() {
    let box = $('.plus-table').on('click', '.fa-times', function() {
        if (box.find('tbody tr').length === 1) {
            return;
        }
        $(this).closest('tr').remove();
    }).on('click', '.fa-plus', function() {
        const tr = box.find('tbody tr').eq(0).clone(true, true);
        box.find('tbody').append(tr);
        tr.find('.form-control').val('');
    });
}
$(function() {
    $('.check-btn').on('click',function(e) {
        e.preventDefault();
        let $this = $(this);
        let box = $this.closest('.check-box');
        if (box.hasClass('checked')) {
            return;
        }
        let val = $this.data('toggle').split('|');
        postJson($this.attr('href'), data => {
            if (data.code != 200) {
                parseAjax(data);
                return;
            } 
            box.toggleClass('checked');
            $this.html(`${val[1]}<em>已连续签到${data.data.running}天</em>`);
        })
    });
});