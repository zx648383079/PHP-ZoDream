function bindNewNote(baseUri: string) {
    $(".new-item").on('input propertychange', 'textarea', function() {
        let $this = $(this),
            max = $this.attr('max-length'),
            length = $this.val().toString().length;
        $this.closest('.new-item').find('.item-action .length-box').text(length + '/' + max);
    }).on('keydown', 'textarea', function(this: HTMLTextAreaElement, e) {
        if (e.code === 'Tab') {
            e.preventDefault();
            const position = this.selectionStart + 1;
            this.value = this.value.substring(0, this.selectionStart) + "\t" + this.value.substring(this.selectionStart);
            this.selectionStart = position;
            this.selectionEnd = position;
            this.focus();
        }
    }).on('click', '.visbile-toggle', function() {
        const $this = $(this);
        if ($this.hasClass('fa-eye')) {
            $this.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $this.addClass('fa-eye').removeClass('fa-eye-slash');
        }
    }).on('click', ' .item-action .fa-check', function() {
        const target = $(this).closest('.new-item');
        let box = target.find('textarea'),
            content = box.val();
        if (content.length < 1) {
            Dialog.tip('请输入内容');
            return;
        }
        postJson(baseUri, {
            content: content,
            status: target.find('.visbile-toggle').hasClass('fa-eye') ? 1 : 0
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