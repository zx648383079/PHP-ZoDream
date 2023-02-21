function bindNewNote(baseUri: string) {
    $(".new-item textarea").on('input propertychange', function() {
        let $this = $(this),
            max = $this.attr('max-length'),
            length = $this.val().length;
        $this.closest('.new-item').find('.item-action .length-box').text(length + '/' + max);
    }).on('keydown', function(e) {
        if (e.keyCode === 9) {
            e.preventDefault();
            let position = this.selectionStart + 4;
            this.value = this.value.substr(0, this.selectionStart) + "\t" + this.value.substr(this.selectionStart);
            this.selectionStart = position;
            this.selectionEnd = position;
            this.focus();
        }
    });
    $(".new-item .item-action .fa-check").on('click',function() {
        let box = $(this).closest('.new-item').find('textarea'),
            content = box.val();
        if (content.length < 1) {
            Dialog.tip('请输入内容');
            return;
        }
        postJson(baseUri, {
            content: content
        }, function(data: any) {
            if (data.code == 200) {
                box.val('');
                Dialog.tip('记录成功！');
                return;
            }
            parseAjax(data);
        });
    });
    $('.flex-box').on('click', '.fa-times', function(e) {
        e.preventDefault();
        let $this = $(this),
            tip = $this.attr('data-tip') || '确定删除这条数据？';
        if (!confirm(tip)) {
            return;
        }
        postJson($this.attr('href'), function(data) {
            if (data.code == 200) {
                $this.closest('.item').remove();
            }
        });
    });
}
$(function() {
    let initAlpha: number;
    window.addEventListener('deviceorientation', e => {
        if (!e.alpha) {
            return;
        }
        if (typeof initAlpha == 'undefined' || Math.abs(e.gamma) > 45) {
            initAlpha = e.alpha; // 设置初始方向
        }
        $('.flex-box .item').css('transform', 'rotate(' + (e.alpha - initAlpha) + 'deg)');
    });
    $(".more-load").lazyload({
        mode: 1,
        callback: function(moreEle: JQuery) {
            if (moreEle.data('is_loading')) {
                return;
            }
            moreEle.data('is_loading', true);
            let page: number = parseInt(moreEle.data('page') || '0') + 1;
            let url = moreEle.data('url');
            let target = moreEle.data('target');
            $.getJSON(url, {
                page: page
            }, function (data) {
                moreEle.data('is_loading', false)
                if (data.code != 200) {
                    return;
                }
                $(target).append(data.data.html);
                moreEle.data('page', page);
                if (!data.data.has_more) {
                    moreEle.remove();
                }
            });
        }
    });
});