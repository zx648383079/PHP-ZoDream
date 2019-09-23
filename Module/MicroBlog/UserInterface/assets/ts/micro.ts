function bindMicroPage(baseUri: string) {
    $('.micro-publish').on('submit', 'form', function() {
        let $this = $(this);
        $.post($this.attr('action'), $this.serialize(), function (data) {
            if (data.code == 200) {
                window.location.reload();
                return;
            }
            if (data.code == 302) {
                window.location.href = data.url;
                return;
            }
            alert(data.message);
        }, 'json');
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
        $.get(baseUri + 'comment', {
            id: box.data('id')
        }, function(html) {
            comment.html(html);
        });
    }).on('click', '[data-action=recommend]', function() {
        let $this = $(this),
            box = $this.closest('.micro-item');
        $.getJSON(baseUri + 'recommend', {
            id: box.data('id')
        }, function(data) {
            if (data.code == 200) {
                $this.text('赞' + data.data).toggleClass('active');
            }
            if (data.code == 302) {
                window.location.href = data.url;
                return;
            }
        });
    }).on('click', '.reply-input .btn', function(e) {
        e.preventDefault();
        let $this = $(this);
        let url = $this.attr('href');
        let content = $this.closest('.reply-input').find('textarea').val() as string;
        if (content.length < 0) {
            return;
        }
        postJson(url, {
            content
        }, res => {
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
