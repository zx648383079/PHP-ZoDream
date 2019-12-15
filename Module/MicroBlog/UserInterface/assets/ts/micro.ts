function bindMicroPage() {
    $('.micro-publish').on('submit', 'form', function() {
        let $this = $(this);
        postJson($this.attr('action'), $this.serialize(), data => {
            if (data.code == 200) {
                window.location.reload();
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
    });
}
