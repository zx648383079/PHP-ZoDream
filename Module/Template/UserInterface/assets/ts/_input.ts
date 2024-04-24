class EditorHtmlHelper {
    private static _guid = 0;
    private static _cacheData: any = {};
    public static guid(): number {
        return this._guid ++;
    }

    public static shimmed(val?: any): string {
        if (!val) {
            val = this.guid();
        }
        return `_zreinput="${val}"`;
    }

    public static getShimmed(element: HTMLElement|JQuery<HTMLElement>): string {
        if (!(element instanceof HTMLElement)) {
            return element.attr('_zreinput');
        }
        return element.getAttribute('_zreinput');
    }

    public static selector(): string {
        return '[_zreinput^=""]';
    }

    public static clear() {
        this._cacheData = {};
    }

    public static set(key: string, data: any): string {
        this._cacheData[key] = data;
        return key;
    }

    public static get<T>(key: string): T {
        return this._cacheData[key];
    }

    public static value(val: any, def: any = ''): string {
        if (typeof val === 'undefined') {
            return def;
        }
        if (val === null) {
            return def;
        }
        return val;
    }

    public static join(...items: any[]): string {
        return items.map(val => this.value(val)).join('');
    }

    public static mapJoinHtml(data: object, cb: (val: any, key?: string|number) => string): string {
        let html = '';
        EditorHelper.eachObject(data, (val, key) => {
            html += cb(val, key);
        });
        return html;
    }

    public static input(id: string|IEditorInput, label: string, content: string, cls: string = 'control-inline-group'): string {
        const shimmed = this.shimmed(typeof id === 'object' ? id.shimmed : undefined);
        const idVal = typeof id === 'object' ? id.shimmed : id;
        const hidden = typeof id !== 'object' || !id.isUpdated ? ' hidden' : '';
        return `<div ${shimmed} class="${cls}"><i class="control-updated-tag${hidden}"></i><label for="${idVal}">${label}</label>${content}</div>`;
    }

    public static buttonGroup(...items) {
        return this.join('<div class="btn-group control-offset">', ...items, '</div>');
    }

    public static button(text: string, cls: string) {
        return `<button type="button" class="btn ${cls}">${text}</button>`;
    }


    
    public static nameToId(name: string) {
        return name.replace(/\[/g, '_').replace(/\]/g, '');
    }

    public static renderAttribute(item: IVisualInput): string {
        if (!item || !item.option) {
            return;
        }
        const items = [];
        EditorHelper.eachObject(item.option, (val, key) => {
            if (typeof key === 'string' && key.indexOf('data-') === 0) {
                items.push(`${key}="${val}"`);
            }
        });
        return items.join(' ');
    }

    public static renderClass(item: IVisualInput, hasKey = true): string {
        if (!item) {
            return;
        }
        const items = [];
        if (item.class) {
            items.push(item.class);
        }
        if (item.option?.tab) {
            items.push(item.option.tab);
        }
        if (item.hidden) {
            items.push('hidden');
        }
        if (items.length === 0) {
            return '';
        }
        const cls = items.join(' ');
        return hasKey ? ` class="${cls}"` : cls;
    }

    private static isHiddenTab(tab: string, items: any[]): boolean {
        for (const item of items) {
            if (tab.indexOf(item.tab) === 0) {
                return tab !== item.match;
            }
        }
        return false;
    }

    public static render(form: IVisualInput[]): string {
        return this.renderControlItems(form).map(i => i.render()).join('');
    }

    public static renderControlItems(form: IVisualInput[]): IEditorInput[] {
        const tabItems = [];
        for (const item of form) {
            if (!item.option) {
                continue;
            }
            if (item.option['data-tab']) {
                tabItems.push({tab: item.option['data-tab'], match: item.option['data-tab'] + '-' + (item.value ?? item.items[0].value)});
                continue;
            }
            if (!item.option.tab) {
                continue;
            }
            item.hidden = this.isHiddenTab(item.option.tab, tabItems);
        }
        return form.map(this.renderControl.bind(this));
    }

    public static formData(target: JQuery<HTMLElement>): Object;
    public static formData(target: JQuery<HTMLElement>, isObject: false): string;
    public static formData(target: JQuery<HTMLElement>, isObject: true): Object;
    public static formData(target: JQuery<HTMLElement>, prefix: string): Object;
    public static formData(target: JQuery<HTMLElement>, isObject: string|boolean = true): any {
        const that = this;
        const prefix = typeof isObject === 'string' ? isObject : '';
        isObject = typeof isObject === 'string' || isObject;
        const data: any = isObject ? {} : [];
        const queryFn = (box: JQuery<HTMLElement>) => {
            box.children().each(function(this: HTMLInputElement) {
                const nodeName = this.nodeName.toLowerCase();
                if (['input','textarea','select'].indexOf(nodeName)) {
                    if (this.type && ['radio', 'checkbox'].indexOf(this.type) >= 0 && !this.checked) {
                        return;
                    }
                    if (!this.name) {
                        return;
                    }
                    const name = prefix && this.name.indexOf(prefix) === 0 ? this.name.substring(prefix.length) : this.name;
                    if (isObject) {
                        data[name] = $(this).val();
                        return;
                    }
                    data.push(encodeURIComponent(name) + '=' +
                            encodeURIComponent( $(this).val().toString() ));
                    return;
                }
                if (this.classList.contains('panel-item')) {
                    queryFn($(this).find('.tab-body .tab-item'));
                    return;
                }
                if (this.classList.contains('expand-box')) {
                    queryFn($(this).children('.expand-body'));
                    return;
                }
                const shimmed = that.getShimmed(this);
                if (!shimmed) {
                    return;
                }
                const control = that.get<IEditorInput>(shimmed);
                if (isObject) {
                    data[control.name] = control.value;
                    return;
                }
                data.push(encodeURIComponent(control.name) + '=' +
                        encodeURIComponent( typeof control.value === 'object' ? JSON.stringify(control.value) : that.value(control.value) ));
                return;
            });
        };
        queryFn(target);
        return isObject ? data : data.join('&');
    }

    private static renderForm(form: IVisualInput[], data?: any): string {
        return this.render(form.map(item => {
            return {...item, value: this.inputValue(data, item.name)};
        }));
    }

    private static inputValue(data: any, key: string): any {
        if (typeof data !== 'object' || !key) {
            return undefined;
        }
        if (Object.prototype.hasOwnProperty.call(data, key)) {
            return data[key];
        }
        if (key.indexOf('item_') !== 0) {
            return undefined;
        }
        key = key.substring(5);
        return Object.prototype.hasOwnProperty.call(data, key) ? data[key] : undefined;
    }

    public static renderControl(input: IVisualInput): IEditorInput {
        const shimmed = this.guid();
        const id = input.name + '_' + shimmed;
        const maps: {
            [key: string]: any,
        } = {
            group: EditorGroupControl,
            align: EditorAlignControl,
            border: EditorBorderControl,
            textAlign: EditorTextAlignControl,
            color: EditorColorControl,
            background: EditorBackgroundControl,
            icon: EditorIconControl,
            number: EditorNumberControl,
            textarea: EditorTextareaControl,
            size: EditorSizeControl,
            range: EditorRangeControl,
            position: EditorPositionControl,
            bound: EditorBoundControl,
            switch: EditorSwitchControl,
            image: EditorImageControl,
            images: EditorImagesControl,
            multiple: EditorMultipleControl,
            tree: EditorTreeControl,
            html: EditorHtmlControl,
            text: EditorTextControl,
            select: EditorSelectControl,
            shadow: EditorShadowControl
        };
        let control: IEditorInput;
        if (maps[input.type]) {
            control = new maps[input.type](input);
        } else {
            const func = this[input.type + 'Execute'];
            control = typeof func === 'function' ? func.call(this, input, id) : new EditorTextControl(input);
        }
        control.shimmed = input.type + shimmed;
        control.label = input.label;
        if (!control.value) {
            control.value = input.value;
        }
        control.name = input.name;
        this.set(control.shimmed, control);
        return control;
        // throw new Error(`input type error:[${input.type}]`);
    }

    public static readyControl(box: JQuery<HTMLElement>, manager: IEditorInputGroup) {
        const that = this;
        box.children().each(function() {
            const shimmed = that.getShimmed(this);
            if (!shimmed) {
                return;
            }
            that.get<IEditorInput>(shimmed).ready($(this) as any, manager);
        });
    }

    public static bindInputEvent(box: JQuery<HTMLElement>, manager: IEditorInputGroup) {
        const that = this;
        box
        // switch
        // .on('click', '.switch-input', function() {
        //     const $this = $(this);
        //     const checked = !$this.hasClass('checked');
        //     $this.toggleClass('checked', checked);
        //     $this.find('.switch-label').text(checked ? $this.data('on') : $this.data('off'));
        //     const val = checked ? 1 : 0;
        //     $this.find('input').val(val).trigger('change');
        //     const box = $this.closest('.control-inline-group');
        //     box.trigger(EditorEventInputChange, val)
        //     if ($this.data('tab')) {
        //         box.trigger(EditorEventTabToggle, [$this.data('tab'), $this.val()]);
        //     }
        // })
        // .on(EditorEventInputReset, '.switch-input', function() {
        //     $(this).trigger('click');
        // })
        // popup
        .on('click', '.control-popup-target', function() {
            const $this = $(this);
            const popup = $this.next('.control-popup');
            if ($this.closest('.dialog-box').length > 0) {
                popup.toggleClass('popup-top', $this.offset().top + $this.height() > window.innerHeight - popup.height())
            }
            popup.toggleClass('open');
        })
        .on(EditorEventInputChange, '.control-popup-target', function(e, val) {
            e.stopPropagation();
            $(this).closest('.control-inline-group').trigger(EditorEventInputChange, val);
            $(this).find('input').val(val);
        })
        .on(EditorEventInputChange, '.control-popup', function(e, val) {
            e.stopPropagation();
            $(this).prev('.control-popup-target').trigger(EditorEventInputChange, val);
        })
        .on(EditorEventInputReset, '.control-popup-target', function(e) {
            e.stopPropagation();
            const $this = $(this);
            $this.find('input').val('');
        })
        .on(EditorEventInputReset, '.control-popup', function(e) {
            e.stopPropagation();
            if (e.target !== this) {
                return;
            }
            const $this = $(this);
            const popupType = $this.data('popup');
            if (popupType === 'color') {
                //EditorHtmlHelper.colorPopupColor($this, '#0000');
                $this.find('.tab-bar-target input').val('');
                return;
            }
            $this.children('.control-inline-group,.control-line-group').children('label').next().trigger(EditorEventInputReset);
        })
        .on('click', '.control-popup .popup-action .btn', function() {
            const $this = $(this);
            if ($this.hasClass('btn-danger')) {
                // $this.closest('.control-popup').trigger(EditorEventInputReset).prev('.control-popup-target').trigger(EditorEventInputReset).
                // prev().prev('.control-updated-tag').addClass('hidden');
                $this.closest('.control-popup').siblings('.control-updated-tag').trigger('click');
                return;
            }
        })
        // icon
        
        // select
        
        // color
        
        // select
       
        // tabbar 
        .on('click', '.tab-bar .item', function() {
            const $this = $(this);
            $this.addClass('active').siblings().removeClass('active');
            $this.closest('.tab-bar').siblings('.tab-bar-target').removeClass('active').eq($this.index()).addClass('active');
        })
        // 图片上传
        
        // radio
        .on(EditorEventInputReset, '.control-row', function(e) {
            if (!$(e.target).hasClass('control-row')) {
                return;
            }
            $(this).children().trigger(EditorEventInputReset);
        })
        
        // .on(EditorEventInputReset, '.radio-control-item', function() {
        //     const $this = $(this);
        //     if ($this.hasClass('checked')) {
        //         $this.trigger('click');
        //     }
        // })
        .on('change', 'select', function() {
            const $this = $(this);
            const val = $this.val();
            const group = $this.closest('.control-inline-group');
            group.trigger(EditorEventInputChange, val);
            if ($this.data('tab')) {
                group.trigger(EditorEventTabToggle, [$this.data('tab'), val]);
            }
        }).on(EditorEventInputReset, 'select', function(e) {
            e.stopPropagation();
            $(this).val('');
        }).on(EditorEventTabToggle, '.control-inline-group,.control-line-group', function(_, tab: string, val: any) {
            const match = `${tab}-${val}`;
            $(this).siblings().each(function(this: HTMLDivElement) {
                for (let i = 0; i < this.classList.length; i++) {
                    const item = this.classList[i];
                    if (item.indexOf(tab) === 0) {
                        $(this).toggleClass('hidden', item !== match);
                        break;
                    }
                }
            });
        }).on(EditorEventInputChange, '.control-inline-group,.control-line-group', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const control = that.get<IEditorInput>(that.getShimmed(this));
            $this.find('.control-updated-tag:first').toggleClass('hidden', !control.isUpdated);
        })
        .on('click', '.control-updated-tag', function() {
            const $this = $(this);
            const control = that.get<IEditorInput>(that.getShimmed($this.parent()));
            control.reset();
            $this.addClass('hidden');
        })
        // 多项添加
        .on('click', '.multiple-container .multiple-control-header', function() {
            $(this).closest('.multiple-control-item').toggleClass('open').siblings().removeClass('open');
        })
        // flip 
        .on('click', '.flip-container .flip-back-btn', function() {
            $(this).closest('.flip-container').trigger(EditorEventFlipFinish, false);
        })
        .on('click', '.flip-container .flip-save-btn', function() {
            $(this).closest('.flip-container').trigger(EditorEventFlipFinish, true);
        })
        .on(EditorEventFlipToggle, '.flip-container', function(_, callback: (data: any) => void) {
            const $this = $(this);
            $this.addClass('flip-toggle');
            const form = $this.find('.flip-body');
            $this.one(EditorEventFlipFinish, (_, res: boolean) => {
                $this.removeClass('flip-toggle');
                if (res) {
                    callback(that.formData(form, 'item_'));
                }
                form.empty();
            });
        });
       
        
    }
}
