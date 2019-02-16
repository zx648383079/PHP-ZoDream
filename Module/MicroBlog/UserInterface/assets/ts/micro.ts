function bindMicroPage(baseUri: string) {
    $('.micro-publish form').submit(function() {
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
    });
    $('.micro-item [data-action=comment]').click(function() {
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
    });
    $('.micro-item [data-action=recommend]').click(function() {
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
    });
}
