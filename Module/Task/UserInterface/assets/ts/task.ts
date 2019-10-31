class Timer {

    public element: JQuery;

    public startAt: Date;

    private _handle: number;

    public dialog: JQuery;

    public start(element: JQuery, start_at: string | number, cb: () => void) {
        this.stop();
        this.element = element;
        this.dialog = undefined;
        this.startAt = new Date(typeof start_at == 'number' ? start_at * 1000 : start_at);
        let that = this;
        this._handle = setInterval(function() {
            that._showTime();
            cb && cb();
        }, 500);
    }

    private _showTime() {
        const diff = new Date().getTime() - this.startAt.getTime();
        this.element.text(Timer.format(diff));
        if (!this.dialog) {
            return;
        }
        const total = this.element.closest('.task-item').data('time');
        if (total < 1) {
            return;
        }
        this.dialog.find('.timer-box').text(Timer.format(total * 60000 - diff));
    }

    public stop() {
        if (this.element) {
            this.element.text('统计中。。。');
            clearInterval(this._handle);
        }
        this.element = undefined;
        this._handle = 0;
        this.dialog = undefined;
    }
    
    public static format(time: number): string {
        time = Math.floor(time / 1000);
        let s = time % 60,
            m = Math.floor(time / 60) % 60,
            h = Math.floor(time / 3600),
            b = function(t) {
                return t < 10 ? '0' + t : t;
            };
        return (h > 0 ? b(h) + ':' : '') + b(m) + ':' + b(s);
    }
}

let timer = new Timer();
let isLoading = false;
let times = 120;

function bindTask(baseUri: string) {
    if ('Notification' in window) {
        Notification.requestPermission();
    }
    refreshPanel(baseUri);
    $('.panel ').on('click', '.task-item .fa-pause-circle', function() {
        pauseTask(baseUri, $(this).closest('.task-item'));
    }).on('click', '.task-item .fa-play-circle', function() {
        playTask(baseUri, $(this).closest('.task-item'));
    }).on('click', '.task-item .fa-stop-circle', function() {
        stopTask(baseUri, $(this).closest('.task-item'));
    }).on('click', '.task-item .name', function() {
        let row = $(this).closest('.task-item');
        if (!row.hasClass('active')) {
            return;
        }
        timerBox.find('.timer-tip').text($(this).text());
        timerBox.show();
        timer.dialog = timerBox;
    });
    let box = $('.dialog-panel');
    $('[data-action=add]').click(function(e) {
        e.preventDefault();
        box.show();
    });
    box.on('click', '.task-item', function() {
        $(this).toggleClass('active');
    }).on('click', '.btn', function() {
        let items = [];
        box.find('.task-item.active').each(function() {
            items.push($(this).removeClass('active').data('id'));
        });
        box.hide();
        if (items.length < 1) {
            return;
        }
        postJson(baseUri + 'task/batch_add', {
            id: items
        }, res => {
            if (res.code !== 200) {
                parseAjax(res);
                return;
            }
            refreshPanel(baseUri);
        });
    });
    let timerBox = $('.dialog-timer');
    let startPoint = null;
    timerBox.on('click', '.timer-close', function(e) {
        e.preventDefault();
        timerBox.hide();
        stopTask(baseUri, timer.element.closest('.task-item'));
    }).on('click', '.timer-pause', function(e) {
        e.preventDefault();
        timerBox.hide();
        pauseTask(baseUri, timer.element.closest('.task-item'));
    }).on('touchstart', function(e) {
        startPoint = {x: e.touches[0].clientX, y: e.touches[0].clientY};
    }).on('touchmove', function(e) {
        const diff = Math.min(e.changedTouches[0].clientY - startPoint.y, 0);
        timerBox.css('transform', 'translateY('+ diff +'px)');
        timerBox.addClass('closing');
    }).on('touchend', function(e) {
        const diff = startPoint.y - e.changedTouches[0].clientY;
        const h = $(window).height();
        if (diff < h / 3) {
            animation(-diff, 0, i => {
                timerBox.css('transform', 'translateY('+ i +'px)');
            }, () => {
                timerBox.removeClass('closing');
            });
            return;
        }
        animation(-diff, -h, i => {
            timerBox.css('transform', 'translateY('+ i +'px)');
        }, () => {
            timerBox.removeClass('closing');
            timerBox.hide();
            stopTask(baseUri, timer.element.closest('.task-item'));
        });
    });
}

function animation(
    start: number, end: number,
    callHandle: (i: number) => void, endHandle?: () => void) {
    const diff = start > end ? -1 : 1;
    let step = 1;
    const handle = setInterval(() => {
        start += (step ++) * diff;
        if ((diff > 0 && start >= end) || (diff < 0 && start <= end)) {
            clearInterval(handle);
            callHandle(end);
            endHandle && endHandle();
            return;
        }
        callHandle(start);
    }, 16);
}

function refreshPanel(baseUri: string) {
    timer.stop();
    $.get(baseUri + 'home/panel', html => {
        $('.panel .panel-body').html(html);
        $('.panel .task-item').each(function() {
            let item = $(this),
                time = item.find('.time');
            if (item.hasClass('active')) {
                timer.start(time, parseInt(time.data('start'), 10), () => {
                    checkTask(baseUri, item);
                });
                return;
            }
            time.text(Timer.format(parseInt(time.text()) * 1000));
        });
    });
}

function checkTask(baseUri: string, element: JQuery) {
    times -- ;
    if (isLoading) {
        return;
    }
    if (times > 0) {
        return;
    }
    isLoading = true;
    times = 120;
    postJson(baseUri + 'task/check', {
        id: element.data('id')
    }, function(data) {
        isLoading = false;
        if (data.code == 200 && data.data) {
            refreshPanel(baseUri);
            Dialog.notify('提示', data.messages);
            alert(data.messages);
        }
    });
}

function pauseTask(baseUri: string, element: JQuery) {
    timer.stop();
    postJson(baseUri + 'task/pause', {
        id: element.data('id')
    }, function(data) {
        if (data.code == 200) {
            refreshPanel(baseUri);
        }
    });
}

function playTask(baseUri: string, element: JQuery) {
    postJson(baseUri + 'task/play', {
        id: element.data('id')
    }, function(data) {
        if (data.code == 200) {
            refreshPanel(baseUri);
        }
    });
}

function stopTask(baseUri: string, element: JQuery) {
    if (element.hasClass('active')) {
        timer.stop();
        postJson(baseUri + 'task/stop', {
            id: element.data('id')
        }, _ => {
            refreshPanel(baseUri);
        });
        return;
    }
    if (!confirm('是否确定结束此任务？')) {
        return;
    }
    postJson(baseUri + 'task/stop_task', {
        id: element.data('id')
    }, _ => {
        refreshPanel(baseUri);
    });
}

function bindRecord() {
    $('#today').datetimer({format: 'yyyy-mm-dd'});
    $('.search-box form').submit(function() {
        let $this = $(this);
        $.get($this.attr('action'), $this.serialize(), html => {
            $('.chart-box').html(html);
        });
        return false;
    }).trigger('submit');
}

function bindReview() {
    $('#today').datetimer({format: 'yyyy-mm-dd'});
    $('.search-box form').submit(function() {
        let $this = $(this);
        $.get($this.attr('action'), $this.serialize(), html => {
            $('.review-box').html(html);
        });
        return false;
    }).trigger('submit');
}