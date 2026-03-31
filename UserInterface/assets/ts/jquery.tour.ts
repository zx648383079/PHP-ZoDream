interface DialogLeadTourStep {
    before?: () => boolean,// 进行一些跳转
    selector: string|HTMLElement;
    content: string;
}

interface LeadTourOption {
    title?: string;
    confirmText?: string;
    backText?: string;
    nextText?: string;
    cancelText?: string;
    items?: DialogLeadTourStep[];
}

class DefaultLeadTourOption implements LeadTourOption {
    confirmText = '完成';
    backText = '上一步';
    nextText = '下一步';
    cancelText = '取消';
}
class LeadTour {
    constructor(
        element: JQuery<HTMLElement>,
        options?: LeadTourOption
    ) {
        this.options = $.extend({}, new DefaultLeadTourOption(), options);
        this.guid = `${options?.items?.length}xx${options?.title}`;
        element.on('click', e => {
            e.stopPropagation();
            e.preventDefault();
            this.open();
        });
        $(document).on('pjax:success', _ => {
            this.load();
        });
        this.load();
    }

    private guid: string;
    private options: LeadTourOption;
    private index = -1;
    private element: JQuery|undefined;


    public get canBack() {
        return this.index > 0;
    }

    public get canNext() {
        return this.index < this.options.items.length - 1;
    }

    private set overlayStyle(args: any) {
        this.element?.find('.lead-overlay-container').css(args);
    } 

    private set dialogStyle(args: any) {
        if (!args.display) {
            args.display = '';
        }
        this.element?.find('.lead-dialog-box').css(args);
    }

    private set content(v: string) {
        this.element?.find('.message-body').text(v);
    }

    private set primaryText(v: string) {
        this.element?.find('.dialog-footer .btn-default').text(v);
    }

    private set secondaryText(v: string) {
        this.element?.find('.dialog-footer .btn-secondary').text(v);
    }


    public next() {
        if (!this.canNext) {
            this.close();
            return;
        }
        this.index ++;
        this.prepare();
    }

    public previous() {
        if (!this.canBack) {
            this.close();
            return;
        }
        this.index --;
        this.prepare();
    }

    public mask() {
        window.sessionStorage.setItem('tour_go', `${this.index}|${this.guid}`);
    }

    public load() {
        const cache = window.sessionStorage.getItem('tour_go');
        if (!cache || !cache.endsWith(this.guid)) {
            return;
        }
        window.sessionStorage.removeItem('tour_go');
        this.initiate();
        this.index = parseInt(cache.substring(0, cache.length - this.guid.length - 1));
        this.refresh();
    }

    
    public open() {
        this.index = -1;
        this.initiate();
        this.next();
    }

    public close() {
        this.element?.remove();
        this.element = undefined;
    }

    private initiate() {
        if (!this.element) {
            this.element = $(this.render());
            this.bindEvent();
        }
        $(document.body).append(this.element);
    }

    private bindEvent() {
        this.element?.on('click', '.dialog-footer .btn-default', () => {
            this.next();
        }).on('click', '.dialog-footer .btn-secondary', () => {
            this.previous();
        }).on('click', '.dialog-close', () => {
            this.close();
        });
    }

    private prepare() {
        const option = this.options.items[this.index];
        if (!option.before) {
            this.renderStep(option);
            return;
        }
        this.mask();
        if (option.before()) {
            setTimeout(() => this.load(), 1000);
            return;
        }
    }

    private refresh() {
        this.renderStep(this.options.items[this.index]);
    }

    private renderStep(data: DialogLeadTourStep, level = 0) {
        const target = $(data.selector as string);
        if (target.length === 0) {
            this.close();
            return;
        }
        const offset = target.offset();
        const sTop = target.scrollTop();
        if (level < 3 && (offset.top < 0 || offset.top + target.height() > window.innerHeight)) {
            this.closeModal();
            target.scrollTop(sTop + offset.top - window.innerHeight / 2);
            setTimeout(() => {
                this.renderStep(data, level + 1);
            }, 500);
            return;
        }
        this.openModal(target[0].getBoundingClientRect(), data);
    }

    private closeModal() {
        this.overlayStyle = {
            left: '0px',
            top: '0px',
            height: '0px',
            width: '0px'
        };
        this.dialogStyle = {
            display: 'none'
        };
    }

    private openModal(offset: DOMRect, data: DialogLeadTourStep) {
        this.overlayStyle = {
            left: offset.left + 'px',
            top: offset.top + 'px',
            width: offset.width + 'px',
            height: offset.height + 'px',
        };
        const modalHeight = 190;
        const modalWidth = 320;
        this.dialogStyle = this.computeModalStyle(offset, modalWidth, modalHeight);
        this.content = data.content;
        this.secondaryText = this.canBack ? this.options.backText : this.options.cancelText;
        this.primaryText = this.canNext ? this.options.nextText : this.options.confirmText;
    }

    private computeModalStyle(offset: DOMRect, width: number, height: number): any {
        const bottom = offset.bottom + height + 10;
        const res = {
            left: (window.innerWidth - width) / 2 + 'px',
            top: (window.innerHeight - height) / 2 + 'px',
        };
        if (bottom <= window.innerHeight) {
            res.top = offset.bottom + 10 + 'px';
        } else {
            const top = offset.top - height - 10;
            if (top >= 0) {
                res.top = top + 'px';
            }
        }
        
        const right = offset.right + width + 10;
        if (right <= window.innerWidth) {
            res.left = offset.right + 10 + 'px';
        } else {
            const left = offset.left - width - 10;
            if (left >= 0) {
                res.left = left + 'px';
            }
        }
        return res;
    }

    private render() {
        return `
    <div class="dialog-lead-tour">
        <div class="lead-overlay-container"></div>
        <div class="dialog dialog-box lead-dialog-box">
            <div class="dialog-header">
                <div class="dialog-title">${this.options.title??''}</div>
                <i class="fa fa-close dialog-close"></i>
            </div>
            <div class="dialog-body">
                <div class="message-body">
                
                </div>
            </div>
            <div class="dialog-footer">
                <div class="btn btn-secondary"></div>
                <div class="btn btn-default"></div>
            </div>
        </div>
    </div>`;
    }
}



;(function($: any) {
    $.fn.tour = function(options?: LeadTourOption) {
        return new LeadTour(this, options); 
    };
})(jQuery);