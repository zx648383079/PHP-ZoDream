const DIALOG_CLOSE = 'dialog-close';
function bindMicroPage() {
    let dialogTarget: JQuery;
    let file_upload = new Upload(null, {
        url: UPLOAD_URI,
        name: 'upfile',
        template: '{url}',
        onafter: function(data: any, element: JQuery) {
            if (data.state == 'SUCCESS') {
                element.find('.dialog-body .add-btn').before('<div class="file-item"><img src="'+data.url+'" alt=""><input type="hidden" name="file[]" value="'+data.url+'"><i class="fa fa-times"></i></div>');
                element.show();
            } else if (data.code === 302) {
                location.href = data.url;
            }
            return false;
        }
    });
    let uploadBox = $('.dialog-upload').on(DIALOG_CLOSE, function() {
        uploadBox.hide();
        uploadBox.find('.file-item').remove();
    }).on('click', '.dialog-header .fa-times', function() {
        uploadBox.trigger(DIALOG_CLOSE);
    }).on('click', '.file-item .fa-times', function() {
        $(this).closest('.file-item').remove();
    }).on('click', '.add-btn', function() {
        file_upload.options.filter = '';
        file_upload.start(uploadBox);
    });
    let emojiBox = $('.dialog-emoji').on('click', '.dialog-header .fa-times', function() {
        emojiBox.hide();
    }).on('click', '.dialog-header .tab-header li', function() {
        let $this = $(this);
        $this.addClass('active').siblings().removeClass('active');
        $this.closest('.dialog-emoji').find('.dialog-body .tab-item').eq($this.index()).addClass('active').siblings().removeClass('active');
    }).on('click', '.emoji-item', function() {
        let $this = $(this);
        let area = dialogTarget.find('textarea');
        area.val( area.val() + '[' + $this.attr('title') + ']');
        emojiBox.hide();
    });
    $('.micro-publish').on('submit', 'form', function() {
        let $this = $(this);
        let files = [''];
        uploadBox.find('.file-item input').each(function(this: HTMLInputElement) {
            files.push('file[]=' + this.value);
        });
        postJson($this.attr('action'), $this.serialize() + files.join('&'), data => {
            if (data.code == 200) {
                window.location.reload();
                uploadBox.trigger(DIALOG_CLOSE);
                return;
            }
            if (data.code == 302) {
                window.location.href = data.url;
                return;
            }
            parseAjax(data);
        });
        return false;
    }).on('input propertychange', 'textarea', function() {
        let $this = $(this);
        let tip = $this.closest('.micro-publish').find('.tip');
        let len = ($this.val() as string).length;
        tip.toggle(len > 0).find('em').text(len);
    }).on('click', '.actions .tools .fa-smile', function() {
        let offset = $(this).offset();
        emojiBox.css({
            left: offset.left,
            top: offset.top + 20
        }).show();
        dialogTarget = $(this).closest('.comment-publish,form');
    }).on('click', '.actions .tools .fa-image,.actions .tools .fa-video', function() {
        let $this = $(this);
        file_upload.options.filter = $this.hasClass('fa-image') ? 'image/*' : '';
        file_upload.start(uploadBox);
        let offset = $this.offset();
        uploadBox.css({
            left: offset.left,
            top: offset.top + 20
        });
        uploadBox.find('.file-item').remove();
        dialogTarget = $this.closest('.comment-publish,form');
    });
    $('.micro-item').on('click', '[data-action=comment]', function() {
        let box = $(this).closest('.micro-item'),
            comment = box.find('.comment-box').toggle();
        if (comment.is(':hidden')) {
            return;
        }
        $.get(BASE_URI + 'comment', {
            id: box.data('id')
        }, function(html) {
            comment.html(html);
        });
    }).on('click', '[data-action=recommend]', function() {
        let $this = $(this),
            box = $this.closest('.micro-item');
        $.getJSON(BASE_URI + 'recommend', {
            id: box.data('id')
        }, function(data) {
            if (data.code == 200) {
                $this.text('赞' + (data.data && data.data.recommend_count > 0 ? data.data.recommend_count : '')).toggleClass('active', data.data.is_recommended);
            }
            if (data.code == 302) {
                window.location.href = data.url;
                return;
            }
        });
    }).on('click', '[data-action="dialog"]', function(e) {
        e.preventDefault();
        let $this = $(this);
        let forwardDialog = Dialog.box({
            url: $this.attr('href'),
            title: '转发微博',
            hasYes: false,
            hasNo: false,
            ondone: function() {
                let box = this.find('.forward-box') as JQuery;
                postJson(box.attr('action'), box.serialize(), res => {
                    forwardDialog.close();
                    parseAjax(res);
                });
            }
        });
    }).on('click', '.reply-input .btn', function(e) {
        e.preventDefault();
        let $this = $(this);
        let url = $this.attr('href');
        let box = $this.closest('.reply-input');
        let content = box.find('textarea').val() as string;
        if (content.trim().length < 0) {
            return;
        }
        postJson(url, formData(box), res => {
            if (res.code != 200 || !res.data.url) {
                return;
            }
            $.get(res.data.url, function(html) {
                $this.closest('.comment-box').html(html);
            });
        });
    }).on('input propertychange', '.reply-input textarea', function() {
        let $this = $(this);
        let val = $this.val() as string;
        $this.height(val.split("\n").length * 18);
        $this.closest('.reply-input').find('.btn').toggleClass('btn-disable', val.length < 1);
    }).on('click', '[data-type="reply"]', function(e) {
        e.preventDefault();
        let $this = $(this);
        let prev = $this.closest('.actions');
        if (prev.next('.reply-input').length > 0) {
            prev.next('.reply-input').toggle();
            return;
        }
        let url = $this.attr('href');
        let prefix = $this.attr('title');
        prev.after(`<div class="reply-input">
        <div class="input">
            <textarea name="content">${prefix}:</textarea>
        </div>
        <div class="input-actions">
            <div class="tools">
                <i class="fa fa-smile"></i>
                <i class="fa fa-image"></i>
            </div>
            <a class="btn" href="${url}">回复</a>
        </div>
    </div>`)
    }).on('click', '.attachment li', function() {
        let $this = $(this);
        let box = $this.closest('.attachment');
        box.addClass('media-slider');
        box.find('.media-large img').attr('src', $this.find('img').attr('src'));
    }).on('click', '.attachment .fa-times', function() {
        $(this).closest('.attachment').removeClass('media-slider');
    }).on('click', '.attachment .media-large img', function() {
        $(this).closest('.attachment').removeClass('media-slider');
    });
}
