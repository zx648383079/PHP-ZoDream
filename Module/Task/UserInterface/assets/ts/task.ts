class Timer {

    public element: JQuery;

    public startAt: Date;

    private _handle: number;

    public start(element: JQuery, start_at: string | number) {
        this.stop();
        this.element = element;
        this.startAt = new Date(typeof start_at == 'number' ? start_at * 1000 : start_at);
        let that = this;
        this._handle = setInterval(function() {
            that._showTime();
        }, 500);
    }

    private _showTime() {
        this.element.text(Timer.format(new Date() - this.startAt));
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

function bindTask(baseUri: string) {
    $(".task-item .fa-pause-circle").click(function() {
        pauseTask(baseUri, $(this).closest('.task-item'));
    });
    $(".task-item .fa-play-circle").click(function() {
        playTask(baseUri, $(this).closest('.task-item'));
    });
    $(".task-item .fa-stop-circle").click(function() {
        stopTask(baseUri, $(this).closest('.task-item'));
    });
    $(".task-item").each(function() {
        let item = $(this),
            time = item.find('.time');
        if (item.hasClass('active')) {
            timer.start(time, parseInt(time.data('start')));
            return;
        }
        time.text(Timer.format(parseInt(time.text()) * 1000));
    });
}

function pauseTask(baseUri: string, element: JQuery) {
    timer.stop();
    postJson(baseUri + 'task/pause', {
        id: element.data('id')
    }, function(data) {
        if (data.code == 200) {
            element.removeClass('active').find('.time').text(Timer.format(data.data.time_length * 1000));
        }
    });
}

function playTask(baseUri: string, element: JQuery) {
    postJson(baseUri + 'task/play', {
        id: element.data('id')
    }, function(data) {
        if (data.code == 200) {
            timer.start(element.addClass('active').find('.time'), data.data.start_at)
        }
    });
}

function stopTask(baseUri: string, element: JQuery) {
    if (element.hasClass('active')) {
        timer.stop();
    }
    postJson(baseUri + 'task/stop', {
        id: element.data('id')
    });
    element.remove();
}