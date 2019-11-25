class Timer {

    public element: JQuery;

    public startAt: Date;

    private _handle: number;

    public dialog: JQuery;

    private _timeSpace = 300;

    private _timeDown = 0;

    private _maxTime = 1; // 1分钟

    public start(element: JQuery, start_at: string | number) {
        this.stop();
        this.element = element;
        this.dialog = undefined;
        this.startAt = new Date(typeof start_at == 'number' ? start_at * 1000 : start_at);
        let that = this;
        this._handle = setInterval(function() {
            that._showTime();
        }, this._timeSpace);
    }

    private _showTime() {
        const diff = new Date().getTime() - this.startAt.getTime();
        this.element.trigger(TASK_REFRESH_TIME, Timer.format(diff));
        this._timeDown -= this._timeSpace;
        if (this._timeDown <= 0) {
            this.element.trigger(TASK_CHECK);
            this._timeDown = this._maxTime * 60000;
        }
        if (!this.dialog) {
            return;
        }
        const total = this.element.data('time');
        if (total < 1) {
            return;
        }
        const time = total * 60000 - diff;
        this.dialog.trigger(TASK_TICK_TIMER, Timer.format(Math.max(0, time)));
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
    
    public static format(time: number, format?: string): string {
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
}

let timer = new Timer();
let isLoading = false;
let times = 120;
const TASK_START = 'task_start';
const TASK_PAUSE = 'task_pause';
const TASK_STOP = 'task_stop';
const TASK_CHECK = 'task_check';
const TASK_REFRESH_TIME = 'task_refresh_time';
const TASK_SHOW_TIMER = 'task_show_timer';
const TASK_HIDE_TIMER = 'task_hide_timer';
const TASK_TICK_TIMER = 'task_tick_timer';
const TASK_BATCH_ADD = 'task_batch_add';

function bindTask(baseUri: string) {
    if ('Notification' in window) {
        Notification.requestPermission();
    }
    refreshPanel(baseUri);
    $('.panel').on('dragover', function(ev) {
        ev.stopPropagation();
        ev.preventDefault();
    }).on('drop', function(ev) {
        ev.stopPropagation();
		ev.preventDefault();
        const id = parseInt(ev.originalEvent.dataTransfer.getData('task'), 10);
        if (id < 1) {
            return;
        }
        box.trigger(TASK_BATCH_ADD, id);
    }).on(TASK_START, '.task-item', function() {
        playTask(baseUri, $(this));
    }).on(TASK_PAUSE, '.task-item', function() {
        pauseTask(baseUri, $(this));
    }).on(TASK_STOP, '.task-item', function() {
        stopTask(baseUri, $(this));
    }).on(TASK_REFRESH_TIME, '.task-item', function(_, time: string) {
        $(this).find('.time').text(time);
    }).on(TASK_CHECK, '.task-item', function() {
        checkTask(baseUri, $(this));
    }).on(TASK_SHOW_TIMER, '.task-item', function() {
        let row = $(this);
        if (!row.hasClass('active')) {
            return;
        }
        timerBox.trigger(TASK_SHOW_TIMER, $(this).find('.name').text());
    }).on('click', '.task-item .fa-pause-circle', function() {
        $(this).closest('.task-item').trigger(TASK_PAUSE);
    }).on('click', '.task-item .fa-play-circle', function() {
        $(this).closest('.task-item').trigger(TASK_START);
    }).on('click', '.task-item .fa-stop-circle', function() {
        $(this).closest('.task-item').trigger(TASK_STOP);
    }).on('click', '.task-item .name', function() {
        $(this).closest('.task-item').trigger(TASK_SHOW_TIMER);
    }).on('click', '.last-task-time .time', function() {
        let $this = $(this);
        const time = $this.data('time');
        if ($this.text().indexOf('前') >= 0) {
            $this.text(time);
            return;
        }
        $this.text(Timer.format(new Date().getTime() - new Date(time).getTime(), 'i分s秒') + '前');
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
        box.trigger(TASK_BATCH_ADD, items);
    }).on(TASK_BATCH_ADD, function(_, items) {
        if (!items) {
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
    }).on('mouseenter', '.task-item', function() {
        $(this).attr('draggable', 'true');
    }).on('dragstart', '.task-item', function(ev) {
        ev.originalEvent.dataTransfer.setData('task', $(this).data('id'));
    });
    let timerBox = $('.dialog-timer');
    let startPoint = null;
    timerBox.on(TASK_HIDE_TIMER, function(_, i = 0) {
        animation(i, -$(window).height(), i => {
            timerBox.css('transform', 'translateY('+ i +'px)');
        }, () => {
            timerBox.removeClass('closing');
            timerBox.hide();
            exitFullscreen();
        });
    }).on(TASK_SHOW_TIMER, function(_, name: string) {
        timerBox.find('.timer-tip').text(name);
        timerBox.show();
        timer.dialog = timerBox;
        fullScreen();
        animation(-$(window).height(), 0, i => {
            timerBox.css('transform', 'translateY('+ i +'px)');
        }, () => {
            timerBox.removeClass('closing');
        });
    }).on(TASK_TICK_TIMER, function(_, time: string) {
        timerBox.find('.timer-box').text(time);
    }).on(TASK_PAUSE, function() {
        timer.element.trigger(TASK_PAUSE);
        timerBox.trigger(TASK_HIDE_TIMER);
    }).on(TASK_STOP, function(_, i) {
        timer.element.trigger(TASK_STOP);
        timerBox.trigger(TASK_HIDE_TIMER, i);
    }).on('click', '.timer-close', function(e) {
        e.preventDefault();
        timerBox.trigger(TASK_STOP);
    }).on('click', '.timer-pause', function(e) {
        e.preventDefault();
        timerBox.trigger(TASK_PAUSE);
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
        timerBox.trigger(TASK_STOP, -diff);
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
                timer.start(item, parseInt(time.data('start'), 10));
                return;
            }
            item.trigger(TASK_REFRESH_TIME, Timer.format(parseInt(time.text()) * 1000))
        });
    });
}

function checkTask(baseUri: string, element: JQuery) {
    if (isLoading) {
        return;
    }
    isLoading = true;
    postJson(baseUri + 'task/check', {
        id: element.data('id')
    }, function(data) {
        isLoading = false;
        if (data.code == 200 && data.data) {
            timer.dialog && timer.dialog.trigger(TASK_HIDE_TIMER);
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

function fullScreen() {
    const element = document.documentElement;
    if (element.requestFullscreen) {
        element.requestFullscreen();
    }
}

function exitFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    }
}