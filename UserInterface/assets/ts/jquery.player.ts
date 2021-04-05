const EVENT_TIME_UPDATE = 'timeupdate';
const EVENT_PLAY = 'play';
const EVENT_PAUSE = 'pause';
const EVENT_ENDED = 'ended';
const EVENT_VOLUME_UPDATE = 'volumeupdate';
const EVENT_TAP_PLAY = 'tap_play';
const EVENT_TAP_PAUSE = 'tap_pause';
const EVENT_BOOT = 'boot';
const EVENT_TAP_VOLUME = 'tap_volume';
const EVENT_TAP_TIME = 'tap_time';
class MediaPlayer {
    constructor(
        public element: JQuery,
        public options: IPlayerOption
    ) {
        if (!this.options.src) {
            return;
        }
        this.init();
        this.bindCustomEvent();
    }

    private playerElement: HTMLVideoElement|HTMLAudioElement;
    private playerBar: JQuery;
    private booted = false;
    private volumeLast = 100;
    private duration = 0;

    public on(event: string, callback: Function): this {
        this.options['on' + event] = callback;
        return this;
    }

    public hasEvent(event: string): boolean {
        return this.options.hasOwnProperty('on' + event);
    }

    public trigger(event: string, ... args: any[]) {
        let realEvent = 'on' + event;
        if (!this.hasEvent(event)) {
            return;
        }
        return this.options[realEvent].call(this, ...args);
    }

    private bindCustomEvent() {
        this.on(EVENT_BOOT, () => {
            if (this.booted) {
                return;
            }
            if (this.options.type === 'audio') {
                this.bindAudioEvent();
                return;
            }
            if (this.options.type === 'iframe') {
                this.videoFrame();
                this.booted = true;
                return;
            }
            this.videoPlayer();
            this.initBar(this.element.find('.player-bar'));
            this.bindVideoEvent();
        }).on(EVENT_TAP_PLAY, () => {
            this.playerElement.play();
        }).on(EVENT_TAP_PAUSE, () => {
            this.playerElement.pause();
        }).on(EVENT_TIME_UPDATE, (p: number, t: number) => {
            this.duration = t;
            this.playerBar.find('.time').text(this.formatMinute(p) + '/' + this.formatMinute(t));
            const progess = this.playerBar.find('.slider .progress');
            progess.attr('title', parseInt(p.toString()));
            progess.find('.progress-bar').css('width', p * 100 / t + '%');
        }).on(EVENT_PLAY, () => {
            this.playerBar.find('.icon .fa').addClass('fa-pause').removeClass('fa-play');
        }).on(EVENT_PAUSE, () => {
            this.playerBar.find('.icon .fa').removeClass('fa-pause').addClass('fa-play');
        }).on(EVENT_ENDED, () => {
            this.trigger(EVENT_PAUSE);
        }).on(EVENT_TAP_VOLUME, (v: number) => {
            if (!this.playerElement) {
                return;
            }
            this.playerElement.volume = v / 100;
            this.trigger(EVENT_VOLUME_UPDATE, v);
        }).on(EVENT_VOLUME_UPDATE, (v: number) => {
            const progess = this.playerBar.find('.volume-slider .progress');
            progess.attr('title', parseInt(v.toString()));
            progess.find('.progress-bar').css('width', v + '%');
            let volumeCls = 'fa-volume-up';
            if (v <= 0) {
                volumeCls = 'fa-volume-off';
            } else if (v < 60) {
                volumeCls = 'fa-volume-down';
            }
            this.playerBar.find('.volume-icon .fa').attr('class', 'fa ' + volumeCls);
        }).on(EVENT_TAP_TIME, (p: number) => {
            if (!this.playerElement) {
                return;
            }
            this.playerElement.currentTime = p;
        });
    }

    private init() {
        if (this.options.type === 'audio') {
            this.initAudio();
            return;
        }
        this.initVideo();
    }

    private initAudio() {
        this.audioPlayer();
        this.initBar(this.element);
    }

    private initBar(bar: JQuery) {
        this.playerBar = bar;
        const that = this;
        bar.on('click', '.icon .fa', function() {
            if (!that.booted) {
                that.trigger(EVENT_BOOT);
            }
            that.trigger($(this).hasClass('fa-play') ? EVENT_TAP_PLAY : EVENT_TAP_PAUSE);
        }).on('click', '.volume-icon .fa', function() {
            const $this = $(this);
            if ($this.hasClass('fa-volume-mute') || $this.hasClass('fa-volume-off')) {
                that.trigger(EVENT_TAP_VOLUME, that.volumeLast);
                return;
            }
            if (that.playerElement) {
                that.volumeLast = that.playerElement.volume * 100;
            }
            that.trigger(EVENT_TAP_VOLUME, 0);
        }).on('click', '.slider .progress', function(event) {
            const $this = $(this);
            that.trigger(EVENT_TAP_TIME, (event.clientX - $this.offset().left) * that.duration / $this.width());
        }).on('click', '.volume-slider .progress', function(event) {
            const $this = $(this);
            that.trigger(EVENT_TAP_VOLUME, (event.clientX - $this.offset().left) * 100 / $this.width());
        }).on('click', '.full-icon .fa', function() {
            // const $this = $(this);
            if (that.element.hasClass('player-full')) {
                that.exitFullscreen();
                that.element.removeClass('player-full');
                return;
            }
            that.element.addClass('player-full');
            that.fullScreen();
        });
    }

    private initVideo() {
        this.videoMask();
        this.element.on('click', '.player-mask', () => {
            this.trigger(EVENT_BOOT);
            if (this.playerElement) {
                this.trigger(EVENT_TAP_PLAY);
            }
        });
    }

    private bindAudioEvent() {
        if (this.booted) {
            return;
        }
        this.booted = true;
        this.playerElement = document.createElement('audio');
        this.playerElement.src = this.options.src;
        this.playerElement.addEventListener('timeupdate', () => {
            if (isNaN(this.playerElement.duration) || !isFinite(this.playerElement.duration) || this.playerElement.duration <= 0) {
                this.trigger(EVENT_TIME_UPDATE, 0, 0);
                return;
            }
            this.trigger(EVENT_TIME_UPDATE, this.playerElement.currentTime, this.playerElement.duration);
        });
        this.playerElement.addEventListener('ended', () => {
            this.trigger(EVENT_ENDED);
        });
        this.playerElement.addEventListener('pause', () => {
            this.trigger(EVENT_PAUSE);
        });
        this.playerElement.addEventListener('play', () => {
            this.trigger(EVENT_PLAY);
        });
        this.trigger(EVENT_VOLUME_UPDATE, this.playerElement.volume * 100);
    }

    private bindVideoEvent() {
        if (this.booted) {
            return;
        }
        this.booted = true;
        this.playerElement = this.element.find('.player-video')[0] as HTMLVideoElement;
        this.playerElement.addEventListener('timeupdate', () => {
            if (isNaN(this.playerElement.duration) || !isFinite(this.playerElement.duration) || this.playerElement.duration <= 0) {
                this.trigger(EVENT_TIME_UPDATE, 0, 0);
                return;
            }
            this.trigger(EVENT_TIME_UPDATE, this.playerElement.currentTime, this.playerElement.duration);
        });
        this.playerElement.addEventListener('ended', () => {
            this.trigger(EVENT_ENDED);
        });
        this.playerElement.addEventListener('pause', () => {
            this.trigger(EVENT_PAUSE);
        });
        this.playerElement.addEventListener('play', () => {
            this.trigger(EVENT_PLAY);
        });
        this.trigger(EVENT_VOLUME_UPDATE, this.playerElement.volume * 100);
    }
    
    private audioPlayer() {
        this.element.addClass('audio-player');
        this.element.html(`<div class="icon" title="播放">
        <i class="fa fa-play"></i>
    </div>
    <div class="slider">
        <div class="progress" title="0">
            <div class="progress-bar"></div>
        </div>
    </div>
    <div class="time">
        00:00/00:00
    </div>
    <div class="volume-icon">
        <i class="fa fa-volume-up"></i>
    </div>
    <div class="volume-slider">
        <div class="progress" title="100">
            <div class="progress-bar" style="width: 100%;"></div>
        </div>
    </div>`);
    }

    private videoMask() {
        this.element.addClass('video-player');
        this.element.html(`<div class="player-mask" title="此处有视频，点击即可播放">
        <i class="fa fa-play"></i>
    </div>`);
    }

    private videoFrame() {
        this.element.html(`
        <iframe class="player-frame" src="${this.options.src}" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>`);
    }

    private videoPlayer() {
        this.element.html(`<video class="player-video" src="${this.options.src}"></video>
        <div class="player-bar">
            <div class="icon" title="播放">
                <i class="fa fa-play"></i>
            </div>
            <div class="slider">
                <div class="progress" title="0">
                    <div class="progress-bar"></div>
                </div>
            </div>
            <div class="time">
                00:00/00:00
            </div>
            <div class="volume-icon">
                <i class="fa fa-volume-up"></i>
            </div>
            <div class="volume-slider">
                <div class="progress" title="100">
                    <div class="progress-bar" style="width: 100%;"></div>
                </div>
            </div>
            <div class="full-icon">
                <i class="fa fa-expand"></i>
            </div>
        </div>`);
    }

    private formatMinute(time: number): string {
        return this.twoPad(Math.floor(time / 60)) + ':' + this.twoPad(Math.floor(time % 60));
    }

    private twoPad(n: number) {
        const str = n.toString();
        return str[1] ? str : '0' + str;
    }

    private fullScreen() {
        const element = document.documentElement;
        if (element.requestFullscreen) {
            element.requestFullscreen();
        }
    }
    
    private exitFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
}


interface IPlayerOption {
    [key: string]: any;
    src: string;
    type?: 'audio' | 'video' | 'iframe'
}

;(function($: any) {
  $.fn.player = function(option?: IPlayerOption) {
    return new MediaPlayer(this, option);
  };
})(jQuery);