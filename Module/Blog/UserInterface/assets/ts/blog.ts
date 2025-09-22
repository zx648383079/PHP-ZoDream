function formData(item: JQuery): string {
    let data = [];
    item.find('input,textarea,select').each(function(this: HTMLInputElement) {
        if (this.type && ['radio', 'checkbox'].indexOf(this.type) >= 0 && !this.checked) {
            return;
        }
        data.push(encodeURIComponent( this.name ) + '=' +
				encodeURIComponent( $(this).val().toString() ))
    });
    return data.join('&');
}

function bindBlogPage() {
    $('.book-nav').on('click',function () {
        $(this).toggleClass('hover');
    });
    $('.book-search').on('focus', function () {
        $(this).addClass('focus');
    }).on('blur', function () {
        $(this).removeClass('focus');
    });
    $('.book-search .fa-search').on('click',function () {
        let form = $('.book-search');
        if (form.hasClass('focus')) {
            $('.book-search form').submit();
            return;
        }
        form.addClass('focus');
    });
    $('.book-navicon').on('click',function () {
        $('.book-skin').toggleClass('book-collapsed');
    });
    $('.book-search [name=keywords]').on('keypress', function () {
        let keywords = $(this).val();
        if (!keywords) {
            return;
        }
        $.getJSON(BASE_URI + 'home/suggest?keywords=' + keywords, function (data) {
            if (data.code != 200) {
                return;
            }
            let html = '';
            $.each(data.data, function (i, item) {
                html += '<li><a href="' + item.url + '">' + item.title + '</li>'
            });
            $('.book-search .search-tip').html(html);
        });
    });
    // $('.book-search .search-tip').on('click', 'li', function () {
    //     $('.book-search [name=keywords]').val($(this).text());
    //     $('.book-search form').submit();
    // });
    // $('.book-body .book-item').addClass('fade-pre-item').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
    //     $(this).removeClass('fade-item');
    // }).lazyload({
    //     callback: function(item: JQuery, h, b, i: number) {
    //         item.removeClass('fade-pre-item').css('animation-duration', Math.floor(i / 2) * .5 + 1.5 + 's').addClass('fade-item');
    //     }
    // });
    
}

function bindBlog(id: number, type: number, langs = {}) {
    bindLoadImg();
    if (type != 1) {
        // uParse('#content', {
        //     rootPath: '/assets/ueditor'
        // });
        // SyntaxHighlighter.all();
    }
    let dynamicBox = $('.book-dynamic');
    $.get(BASE_URI + 'log', {blog: id}, html => {
        dynamicBox.html(html);
    });
    $.getJSON(BASE_URI + 'counter', {blog: id}, function(data) {
        if (data.code != 200) {
            return;
        }
        $('.book-body .tools .comment b').text(data.data.comment_count);
        $('.book-body .tools .click b').text(data.data.click_count);
        $('.book-body .tools .recommend-blog b').text(data.data.recommend_count);
    });
    let commentBox = $('.book-footer');
    if (commentBox.length > 0) {
        $.get(BASE_URI + 'comment', {
            blog_id: id
        }, function (html) {
            commentBox.html(html);
        });
    }
    $('.book-body .toggle-open').on('click',function() {
        $(this).closest('.book-body').toggleClass('open');
        checkSize();
    }).on('click', 'a', function(e) {
        e.stopPropagation();
    });
    $('.rule-box button').on('click',function() {
        let $this = $(this);
        $.post($this.data('url'), formData($this.closest('.rule-box')), res => {
            if (res.code != 200) {
                alert(res.message);
                return;
            }
            alert(res.message);
            setTimeout(() => {
                if (res.data.refresh) {
                    window.location.reload();
                    return;
                }
                if (res.data.url) {
                    window.location.href = res.data.url;
                }
            }, 500);
        }, 'json');
    });
    $('.recommend-blog').on('click',function () {
        let that = $(this).find('b');
        $.getJSON(BASE_URI + 'recommend', {
            id
        }, function (data) {
            if (data.code == 200) {
                that.text(data.data);
                return;
            }
            parseAjax(data);
        })
    });
    let bookSkin = $('.book-skin');
    $('.book-navicon').on('click',function () {
        bookSkin.toggleClass('book-collapsed');
    });
    
    let side = $('#content').sideNav({
            target: '.book-side-nav',
            contentLength: 20,
            maxLength: 8,
            title: langs['side_title'],
            maxFixedTop: function(box: JQuery, scrollTop: number) {
                let bottom = commentBox.length < 1 ? (bookSkin.offset().top + bookSkin.height()) : commentBox.offset().top;
                const fixed = scrollTop + $(window).height() - box.height() < bottom;
                if (!fixed) {
                    box.css('top', 'auto');
                    return fixed;
                }
                let diff = dynamicBox.offset().top + dynamicBox.height() - scrollTop;
                if (diff <= 0) {
                    diff = 0;
                } else {
                    diff += 20;
                }
                box.css('top', diff + 'px');
                return fixed;
            }
        }),
        checkSize = function () {
            if (side.headers.length < 1) {
                return;
            }
            side.box.toggle(bookBody.hasClass('open') && $(window).width() > 767);
        };
    const bookBody = $('.book-body');
    checkSize();
    $(window).on('resize', function () {
        checkSize();
    });
    bindCopy();
}

function bindLoadImg() {
    $('img.lazy').lazyload({callback: 'img'});
    const imgItems = $('#content img');
    if (imgItems.length < 1) {
        return;
    }
    const imageDialog = Dialog.create({
        type: 'image',
        onrequest(i: number) {
            const item = imgItems.eq(i);
            const src = item.data('src');
            if (src) {
                return src;
            }
            return item.attr('src');
        }
    });
    imgItems.on('click', function() {
        imageDialog.showIndex(imgItems.index(this));
    });
}

function bindCopy() {
    $(document).on('click', '.code-container .code-header *[data-action=copy]', function(e) {
        e.preventDefault();
        const target = $(this).closest('.code-container').find('code');
        navigator.clipboard.writeText(target.text()).then(
            () => {
                Dialog.tip(`Copy successfully`);
            },
            () => {
                Dialog.tip(`Copy failed`);
            }
        );
    }).on('click', '.code-container .code-header *[data-action=full]', function(e) {
        e.preventDefault();
        $(this).closest('.code-container').toggleClass('code-full-screen').removeClass('code-with-minimize');
    }).on('click', '.code-container .code-header *[data-action=minimize]', function(e) {
        e.preventDefault();
        $(this).closest('.code-container').toggleClass('code-with-minimize').removeClass('code-full-screen');
    });

    //let trigger: JQuery;
    // const btn = document.createElement('div');
    // btn.classList.add('copy-btn');
    // btn.innerText = '复制';
    // document.body.append(btn);
    // const copyBtn = $(btn);
    // $('#content code').on('click mouseenter', function () {
    //     const $this = $(this);
    //     if ($this.css('display') !== 'block') {
    //         return;
    //     }
    //     const offset = $this.offset();
    //     copyBtn.css({
    //         right: $(window).width() - offset.left - $this.width(),
    //         top: offset.top,
    //         display: 'block',
    //     });
    //     trigger = $this;
    // });
    // const resetCopy = () => {
    //     setTimeout(() => {
    //         copyBtn.text('复制');
    //     }, 2000);
    // };
    // const clipboard = new ClipboardJS(btn, {
    //     text: function () {
    //       return trigger ? trigger.text() : '';
    //     },
    // });
    // clipboard.on('success', function() {
    //     copyBtn.text('复制成功');
    //     resetCopy();
    // });
    // clipboard.on('error', function() {
    //     copyBtn.text('复制失败');
    //     resetCopy();
    // });
}

function bindBlogComment(id: number, langs = {}) {
    let box = $('#comment-box'),
        sort_order = true,
        getMoreComments = function (page: number, target: JQuery = box) {
            $.get(BASE_URI + 'comment/more', {
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
    $('.comment-item .expand').on('click',function() {
        $(this).parent().parent().toggleClass('active');
    });
    let all_box = $('.book-comments').on('click', '*[data-type=reply]', function() {
        let $this = $(this),
            form_box = $('.book-comment-form');
        $this.parent().append(form_box);
        form_box.find('.title').text(langs['reply_title']);
        form_box.find('.btn-submit').text(langs['reply_btn']);
        form_box.find('input[name=parent_id]').val($this.parents('.comment-item').attr('data-id'));
    }).on('click', '.order span', function() {
        const $this = $(this);
        if ($this.hasClass('active')) {
            return;
        }
        $this.addClass('active').siblings().removeClass('active');
        sort_order = $this.index() < 1;
        getMoreComments(1);
    }).on('click', '.actions .agree', function() {
        const $this = $(this),
            id = $this.closest('.comment-item').data('id');
        $.getJSON(BASE_URI + 'comment/agree', {
            id
        }, function(data: IResponse) {
            if (data.code == 302) {
                window.location.href = data.url;
                return;
            }
            if (data.code != 200) {
                alert(data.message);
                return;
            }
            $this.find('b').text(data.data);
        });
    }).on('click', '.actions .disagree', function() {
        const $this = $(this),
            id = $this.closest('.comment-item').data('id');
        $.getJSON(BASE_URI + 'comment/disagree', {
            id
        }, function(data: IResponse) {
            if (data.code == 302) {
                window.location.href = data.url;
                return;
            }
            if (data.code != 200) {
                alert(data.message);
                return;
            }
            $this.find('b').text(data.data);
        });
    });
    const formBox = $('.book-comment-form').on('click', '.btn-cancel', function() {
        const hotBox = $('.hot-comments');
        if (hotBox.length > 0) {
            hotBox.after(formBox);
        } else {
            all_box.before(formBox);
        }
        formBox.find('textarea').val('');
        formBox.find('.title').text(langs['comment_title']);
        formBox.find('.btn-submit').text(langs['comment_btn']);
        formBox.find('input[name=parent_id]').val(0);
    }).on('change', 'input[name=email]', function() {
        const email = this.value;
        if (!email) {
            return;
        }
        const urlEle = formBox.find('input[name=url]');
        if (urlEle.val()) {
            return;
        }
        postJson(BASE_URI + 'comment/commentator', {
            email
        }, res => {
            if (res.code !== 200) {
                return;
            }
            urlEle.val(res.data.url);
            formBox.find('input[name=name]').val(res.data.name);
        });
    });
    const saveCommentator = () => {
        const emailEle = formBox.find('input[name=email]');
        if (emailEle.length === 0) {
            return;
        }
        localStorage.setItem('b_cs', JSON.stringify({
            e: emailEle.val(),
            n: formBox.find('input[name=name]').val(),
            l: formBox.find('input[name=url]').val()
        }));
    };
    const loadCommentator = () => {
        const emailEle = formBox.find('input[name=email]');
        if (emailEle.length === 0) {
            return;
        }
        const str = localStorage.getItem('b_cs');
        if (!str) {
            return;
        }
        const data = JSON.parse(str);
        emailEle.val(data.e);
        formBox.find('input[name=name]').val(data.n);
        formBox.find('input[name=url]').val(data.l);
    };
    if (formBox.length > 0) {
        loadCommentator();
    }
    $('#comment-form').on('submit', function () {
        const $this = $(this);
        $.post($this.attr('action'), $this.serialize(), function (data) {
            if (data.code == 200) {
                //window.location.reload();
                saveCommentator();
                $this.find('.btn-cancel').trigger('click');
            }
            alert(data.message);
        }, 'json');
        return false;
    });
    box.on('click', '.load-more', function() {
        const $this = $(this);
        getMoreComments($this.data('page'));
        $this.remove();
    });
    getMoreComments(1);
}

$(function() {
    // 关闭圣诞特效
    // new MainStage('christmas-box');
    // let box = $('body'),
    //     reset_bg = function() {
    //         let width = $window.width();

    //     box.css({
    //         'background-position-y': $window.height() - width * .8 + 'px'
    //     });
    // },
    // $window = $(window).resize(function() {
    //     reset_bg();
    // });
    // reset_bg();
});