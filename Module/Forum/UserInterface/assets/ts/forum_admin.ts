function bindForum() {
    const box = $("#classify-box").on('click', '.del', function(e) {
        e.preventDefault();
        if (box.find('tbody tr').length === 1) {
            return;
        }
        $(this).closest('tr').remove();
    });
    $(".add-classify").on('click',function(e) {
        e.preventDefault();
        const tr = box.find('tbody tr').eq(0).clone(true, false);
        box.append(tr);
        tr.find('input').val('');
    });
}