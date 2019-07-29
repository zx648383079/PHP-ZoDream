function bindNavigation() {
    $('.search-box').on('mouseover', '.search-icon', function() {
        $(".search-engine").show();
    });
    $('.self-box').on('click', '.custom-btn', function() {
        let box = $(this).closest('.self-box');
        box.toggleClass('edit-mode');
    }).on('click', '.panel-close', function() {
        $(this).closest('.self-box').removeClass('edit-mode');
    }).on('hover', '.site-item', function() {
        let box = $(this).closest('.self-box');
    });
}

$(function() {
    $(document).on('click', ".tab-box .tab-header .tab-item", function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        $this.closest(".tab-box").find(".tab-body .tab-item").eq($this.index()).addClass("active").siblings().removeClass("active");
    }).on('click', '.nav-bar .nav-toggle', function() {
        $(this).closest('.nav-bar').toggleClass('open');
    });
});