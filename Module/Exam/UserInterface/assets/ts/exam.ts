function bindEditQuestion() {
    let id = $('input[name=id]').val() || 0;
    $('select[name="type"]').change(function() {
        $.get(BASE_URI + 'question/option', {
            id,
            type: $(this).val()
        }, html => {
            $('.option-box').html(html);
        });
    });
    $('input[name=title]').on('change', function() {
        let $this = $(this);
        let title = $this.val();
        if (!title || title.toString().trim().length < 1) {
            return;
        }
        let id = $('input[name=id]').val();
        postJson(BASE_URI + 'question/check', {
            id,
            title 
        }, res => {
            $this.next('a').remove();
            if (res.code !== 200 || !res.data) {
                return;
            }
            $this.after('<a href="' + res.data.url + '" target="_blank">已存在【' + res.data.title + '】</a>')
        });
    });
    $('.option-box').on('click', '.option-item .remove-btn',  function(e) {
        e.preventDefault();
        let box = $(this).closest('.option-item');
        if (box.siblings('.option-item').length < 1) {
            return;
        }
        box.remove();
    }).on('click', '.add-option',  function(e) {
        e.preventDefault();
        let $this = $(this);
        let item = $this.prev('.option-item').clone();
        $this.before(item);
        item.find('input,textarea').val('');
        item.find('select').val(0);
    }).on('change', '.option-item select[name="option[is_right][]"]',  function(e) {
        let $this = $(this);
        if ($this.val() < 1) {
            return;
        }
        let type = $('select[name="type"]').val();
        if (type > 0) {
            return;
        }
        $(this).closest('.option-item').siblings('.option-item').find('select[name="option[is_right][]"]').val(0);
    }).on('change', '.option-item select[name="option[type][]"]',  function(e) {
        let $this = $(this);
        if ($this.val() < 1) {
            return;
        }
        let input = $(this).closest('.option-item').find('input[name="option[content][]"]');
    });

}

function bindDo(baseUri: string) {
    let panel = $('.question-panel').on('change', 'input,textarea', function() {
        saveQuestion(baseUri + 'pager/save', $(this).closest('.question-item'));
    });
}

function bindDoPage(baseUri: string) {
    let panel = $('.question-panel').on('change', 'input,textarea', function() {
        saveQuestion(baseUri + 'page/save', $(this).closest('.question-item'));
    });
    let counter = $('.time-counter');
    let timer = setInterval(() => {
        let diff = new Date().getTime() - new Date(counter.data('start')).getTime();
        let limit = counter.data('limit');
        if (!limit || limit < 1) {
            counter.text('已耗时：' + formatTime(diff));
            return;
        }
        diff = limit * 60000 - diff;
        if (diff <= 0) {
            clearInterval(timer);
            $('.tool-bar a').trigger('click');
            return;
        }
        counter.text('剩余时间：' + formatTime(diff));
    }, 500);
}

function formatTime(time: number, format?: string): string {
    if (isNaN(time)) {
        time = 0;
    }
    time = Math.floor(time / 1000);
    let s = time % 60,
        m = format && format.indexOf('h') < 0 ? Math.floor(time / 60) : (Math.floor(time / 60) % 60),
        h = Math.floor(time / 3600),
        b = function(t) {
            return t < 10 && t >= 0 ? '0' + t : t;
        };
    if (!format) {
        return (h !== 0 ? b(h) + ':' : '') + b(m) + ':' + b(s);
    }
    return format.replace(/h+/, b(h)).replace(/i+/, b(m)).replace(/s+/, b(s));
}

function bindDoQuestion(baseUri: string) {
    $('.question-panel').on('click', '.tool-bar .btn-bar button', function() {
        let $this = $(this);
        let target = $this.data('target');
        if (target) {
            let text = $this.text();
            let show = text.indexOf('显示') >= 0;
            $(target).toggle(show);
            $this.text(show ? text.replace('显示', '收起') : text.replace('收起', '显示'));
            return;
        }
    }).on('click', '.tool-bar .btn-bar a', function(e) {
        let $this = $(this);
        if ($this.data('type') === 'check') {
            e.preventDefault();
            const box = $this.closest('.question-panel').find('.question-item-box');
            $.post($this.attr('href'), questionData(box), res => {
                box.html(res);
            });
            return;
        }
    })
}

function questionData(item: JQuery): string {
    let data = [];
    item.find('input,textarea').each(function(this: HTMLInputElement) {
        if (this.type && ['radio', 'checkbox'].indexOf(this.type) >= 0 && !this.checked) {
            return;
        }
        data.push(encodeURIComponent( this.name ) + '=' +
				encodeURIComponent( $(this).val().toString() ))
    });
    return data.join('&');
}

function saveQuestion(uri: string, item: JQuery, cb?: (data: any) => void) {
    const data = questionData(item);
    if (data.length < 1) {
        return;
    }
    postJson(uri, data, res => {
        if (res.code === 200) {
            cb && cb(res.data);
        }
    });
}

function bindDatePicker(start: string = 'start_at', end: string = 'end_at', format: string = 'yyyy-mm-dd') {
    let startBox = $('[name='+ start +']').datetimer({
        format
    });
    if (end) {
        $('[name='+ end +']').datetimer({
            format,
            min: startBox
        });
    }
}

function bindEditPage() {
    bindDatePicker();
    let box = $('.rule-box').on('click', '.fa-times', function() {
        if (box.find('tbody tr').length === 1) {
            return;
        }
        $(this).closest('tr').remove();
    }).on('click', '.fa-plus', function() {
        box.find('tbody').append(box.find('tbody tr')[0].outerHTML);
    });
}

