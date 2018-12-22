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
        callback: function(item: JQuery, h, b, i: number) {
            item.removeClass('fade-pre-item').css('animation-duration', Math.floor(i / 2) * .5 + 1.5 + 's').addClass('fade-item');
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
    let commentBox = $(".book-footer");
    if (commentBox.length > 0) {
        $.get(baseUri + 'comment', {
            blog_id: id
        }, function (html) {
            commentBox.html(html);
        });
    }
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
        sort_order = true,
        getMoreComments = function (page: number, target: JQuery = box) {
            $.get(baseUri + 'comment/more', {
                blog_id: id,
                page: page,
                order: sort_order ? 'desc' : 'asc',
            }, function (html) {
                if (page < 2) {
                    target.html(html);
                } else {
                    target.append(html);
                }
            });
    }
    $(".comment-item .expand").click(function() {
        $(this).parent().parent().toggleClass("active");
    });
    let all_box = $(".book-comments").on('click', '*[data-type=reply]', function() {
        $(this).parent().append($(".book-comment-form"));
        $(".book-comment-form .title").text("回复评论");
        $(".book-comment-form .btn-submit").text("回复");
        $(".book-comment-form input[name=parent_id]").val($(this).parents('.comment-item').attr('data-id'));
    }).on('click', '.order span', function() {
        let $this = $(this);
        if ($this.hasClass('active')) {
            return;
        }
        $this.addClass('active').siblings().removeClass('active');
        sort_order = $this.index() < 1;
        getMoreComments(1);
    }).on('click', '.actions .agree', function() {
        let $this = $(this),
            id = $this.closest('.comment-item').data('id');
        $.getJSON(baseUri + 'comment/agree', {
            id: id
        }, function(data: IResponse) {
            if (data.code == 302) {
                window.location.href = data.url;
                return;
            }
            if (data.code != 200) {
                alert(data.errors);
                return;
            }
            $this.find('b').text(data.data);
        });
    }).on('click', '.actions .disagree', function() {
        let $this = $(this),
            id = $this.closest('.comment-item').data('id');
        $.getJSON(baseUri + 'comment/disagree', {
            id: id
        }, function(data: IResponse) {
            if (data.code == 302) {
                window.location.href = data.url;
                return;
            }
            if (data.code != 200) {
                alert(data.errors);
                return;
            }
            $this.find('b').text(data.data);
        });
    });
    $(".book-comment-form .btn-cancel").click(function() {
        let hot_box = $(".hot-comments");
        if (hot_box.length > 0) {
            hot_box.after($(".book-comment-form"));
        } else {
            all_box.before($(".book-comment-form"));
        }
        
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