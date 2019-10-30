class Timer {

    public element: JQuery;

    public startAt: Date;

    private _handle: number;

    public start(element: JQuery, start_at: string | number, cb: () => void) {
        this.stop();
        this.element = element;
        this.startAt = new Date(typeof start_at == 'number' ? start_at * 1000 : start_at);
        let that = this;
        this._handle = setInterval(function() {
            that._showTime();
            cb && cb();
        }, 500);
    }

    private _showTime() {
        this.element.text(Timer.format(new Date().getTime() - this.startAt.getTime()));
    }

    public stop() {
        if (this.element) {
            this.element.text('统计中。。。');
            clearInterval(this._handle);
        }
        this.element = undefined;
        this._handle = 0;
    }
    
    public static format(time: number): string {
        time = Math.floor(time / 1000);
        let s = time % 60,
            m = Math.floor(time / 60) % 60,
            h = Math.floor(time / 3600),
            b = function(t) {
                return t < 10 ? '0' + t : t;
            };
        return b(h) + ':' + b(m) + ':' + b(s);
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