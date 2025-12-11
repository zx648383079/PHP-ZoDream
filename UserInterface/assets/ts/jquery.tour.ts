interface DialogLeadTourStep {
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
        element.on('click', e => {
            e.stopPropagation();
            e.preventDefault();
            this.open();
        });
    }

    private options: LeadTourOption;
    private index = -1;
    private element: JQuery;


    public get canBack() {
        return this.index > 0;
    }

    public get canNext() {
        return this.index < this.options.items.length - 1;
    }

    private set overlayStyle(args: any) {
        this.element.find('.lead-overlay-container').css(args);
    } 

    private set dialogStyle(args: any) {
        this.element.find('.lead-dialog-box').css(args);
    }

    private set content(v: string) {
        this.element.find('.message-body').text(v);
    }

    private set primaryText(v: string) {
        this.element.find('.dialog-footer .btn-default').text(v);
    }

    private set secondaryText(v: string) {
        this.element.find('.dialog-footer .btn-secondary').text(v);
    }


    public next() {
        if (!this.canNext) {
            this.close();
            return;
        }
        this.index ++;
        this.renderStep(this.options.items[this.index]);
    }

    public previous() {
        if (!this.canBack) {
            this.close();
            return;
        }
        this.index --;
        this.renderStep(this.options.items[this.index]);
    }

    
    public open() {
        this.index = -1;
        if (!this.element) {
            this.element = $(this.render());
            this.bindEvent();
        }
        $(document.body).append(this.element);
        this.next();
    }

    public close() {
        this.element.remove();
        this.element = undefined;
    }

    private bindEvent() {
        this.element.on('click', '.dialog-footer .btn-default', () => {
            this.next();
        }).on('click', '.dialog-footer .btn-secondary', () => {
            this.previous();
        }).on('click', '.dialog-close', () => {
            this.close();
        });
    }

    private renderStep(data: DialogLeadTourStep, level = 0) {
        const target = $(data.selector as string);
        if (!target) {
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
        if (bottom <= window.innerHeight) {
            return {
                left: offset.left + 'px',
                top: offset.bottom + 10 + 'px'
            };
        }
        const top = offset.top - height - 10;
        if (top >= 0) {
            return {
                left: offset.left + 'px',
                top: top + 'px'
            };
        }
        const right = offset.right + width + 10;
        if (right <= window.innerWidth) {
            return {
                left: offset.right + 10 + 'px',
                top: offset.top + 'px'
            };
        }
        const left = offset.left - width - 10;
        if (left >= 0) {
            return {
                left: left + 'px',
                top: offset.top + 'px'
            };
        }
        return {
            left: (window.innerWidth - width) / 2 + 'px',
            top: (window.innerHeight - height) / 2 + 'px',
        }
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