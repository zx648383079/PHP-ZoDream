function bindBlogPage(baseUri: string) {
    $(".book-nav").click(function () {
        $(this).toggleClass("hover");
    });
    $(".book-search").focus(function () {
        $(this).addClass("focus");
    }).blur(function () {
        $(this).removeClass("focus");
    });
    $(".book-search .fa-search").click(function () {
        let form = $(".book-search");
        if (form.hasClass('focus')) {
            $(".book-search form").submit();
            return;
        }
        form.addClass("focus");
    });
    $(".book-navicon").click(function () {
        $('.book-skin').toggleClass("book-collapsed");
    });
    $(".book-search [name=keywords]").keypress(function () {
        let keywords = $(this).val();
        if (!keywords) {
            return;
        }
        $.getJSON(baseUri + 'home/suggest?keywords=' + keywords, function (data) {
            if (data.code != 200) {
                return;
            }
            let html = '';
            $.each(data.data, function (i, item) {
                html += '<li>' + item + '</li>'
            });
            $(".book-search .search-tip").html(html);
        });
    });
    $(".book-search .search-tip").on('click', 'li', function () {
        $(".book-search [name=keywords]").val($(this).text());
        $(".book-search form").submit();
    });
    $('.book-body .book-item').addClass('fade-pre-item').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
        $(this).removeClass('fade-item');
    }).lazyload({
        callback: function(item: JQuery) {
            item.removeClass('fade-pre-item').addClass('fade-item');
        }
    });
    
}

function bindBlog(baseUri: string, id: number, type: number) {
    if (type != 1) {
        uParse('#content', {
            rootPath: '/assets/ueditor'
        });
        SyntaxHighlighter.all();
    }
    $.get(baseUri + 'comment', {
        blog_id: id
    }, function (html) {
        $(".book-footer").html(html);
    });
    $(".recommend-blog").click(function () {
        let that = $(this).find('b');
        $.getJSON(baseUri + 'recommend', {
            blog_id: id
        }, function (data) {
            if (data.code == 200) {
                that.text(data.data);
                return;
            }
            Dialog.tip(data.message);
        })
    });
    $(".book-navicon").click(function () {
        $('.book-skin').toggleClass("book-collapsed");
    });
    let side = $("#content").sideNav({
            target: '.book-side-nav',
            contentLength: 20
        }),
        checkSize = function () {
            if (side.headers.length < 1) {
                return;
            }
            side.box.toggle($(window).width() > 767);
        };
    checkSize();
    $(window).resize(function () {
        checkSize();
    });
}

function bindBlogComment(baseUri: string, id: number) {
    let box = $("#comment-box"),
        getMoreComments = function (page: number) {
        $.get(baseUri + 'comment/more', {
            blog_id: id,
            page: page
        }, function (html) {
            page ++;
            if (page < 2) {
                box.html(html);
            } else {
                box.append(html);
            }
        });
    }
    $(".comment-item .expand").click(function() {
        $(this).parent().parent().toggleClass("active");
    });
    $(".book-comments").on('click', '*[data-type=reply]', function() {
        $(this).parent().append($(".book-comment-form"));
        $(".book-comment-form .title").text("回复评论");
        $(".book-comment-form .btn-submit").text("回复");
        $(".book-comment-form input[name=parent_id]").val($(this).parents('.comment-item').attr('data-id'));
    });
    $(".book-comment-form .btn-cancel").click(function() {
        $(".hot-comments").after($(".book-comment-form"));
        $(".book-comment-form .title").text("发表评论");
        $(".book-comment-form .btn-submit").text("评论");
        $(".book-comment-form input[name=parent_id]").val(0);
    });
    $("#comment-form").submit(function () {
        $.post($(this).attr('action'), $(this).serialize(), function (data) {
            if (data.code == 200) {
                window.location.reload();
                return;
            }
            alert(data.message);
        }, 'json');
        return false;
    });
    box.on('click', '.load-more', function() {
        let $this = $(this);
        getMoreComments($this.data('page'));
        $this.remove();
    });
    getMoreComments(1);
}