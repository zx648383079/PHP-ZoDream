$(function() {
    $('.tag-table .fa-tag').on('click',function() {
        $(this).parents('tr').toggleClass('danger');
        let nameEle = $(this).prev();
        postJson(nameEle.attr('data-url'), {
            value: nameEle.text()
        });
    });
});