function bindEditService() {
    const box = $('.form-table').on('click', '.fa-times', function() {
        if (box.find('tbody tr').length > 1) {
            $(this).closest('tr').remove();
        }
    }).on('click', '.fa-plus', function(e) {
        e.stopPropagation();
        let tb = box.find('tbody');
        tb.append(tb.find('tr:last').clone());
    });
}

function bindOrder() {
    $('*[data-action=comment]').click(function(e) {
        e.preventDefault();
        const rank = prompt('请输入您对本次服务的评分(1-10)', '10');
        postJson($(this).attr('href'), {
            rank
        });
    });
}