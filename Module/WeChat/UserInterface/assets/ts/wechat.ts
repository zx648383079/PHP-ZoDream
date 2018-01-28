$(function () {
    $(".wx-editor .zd-tab-head .zd-tab-item").click(function() {
        let $this = $(this);
        let index = $this.index();
        let parent = $this.parents('.zd-tab');
        parent.find('.type-input').val(index);
    });
});