class EditorSwitchElement implements IEditorElement {
    constructor(
        private _value = 0,
        public onLabel = '开启',
        public offLabel = '关闭',
    ) {
    }

    private element?: JQuery<HTMLElement>;

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (!this.element) {
            return;
        }
        this.element.toggleClass('checked', this._value > 0);
    }

    public get value(): number {
        return this._value;
    }

    public render(): string {
        return EditorHtmlHelper.join('<div class="switch-input', this.value > 0 ? ' checked' : '' ,'"><span class="switch-control"></span><span class="switch-label">', this.value > 0 ? this.onLabel : this.offLabel ,'</span></div>');
    }

    public ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup) {
        const that = this;
        this.element = box;
        box.on('click', function() {
            const $this = $(this);
            const checked = !$this.hasClass('checked');
            $this.toggleClass('checked', checked);
            $this.find('.switch-label').text(checked ? that.onLabel : that.offLabel);
            that.value = checked ? 1 : 0;
            manager.notify(that);
        });
    }
}

class EditorNumberElement implements IEditorElement {
    constructor(
        private _value?: number,
        public min?: number,
        public max?: number,
        public step = 1,
    ) {
    }

    private element?: JQuery<HTMLElement>;

    public set value(arg: any) {
        if (typeof arg !== 'undefined') {
            arg = EditorHelper.parseNumber(arg);
        }
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (!this.element) {
            return;
        }
        this.element.find('input').val(typeof this._value === 'undefined' ? '' : this._value);
    }

    public get value(): number|undefined {
        return this._value;
    }

    public reset() {
        this.value = undefined;
    }

    public render(): string {
        return EditorNumberControl.numberInput(typeof this._value === 'undefined' ? '' : this._value);
    }

    public ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup) {
        const that = this;
        this.element = box;
        box.on('click', '.action-plus', function() {
            that.value = EditorHelper.parseNumber(this._value) + that.step;
            manager.notify(that);
        })
        .on('click', '.action-minus', function() {
            that.value = EditorHelper.parseNumber(this._value) - that.step;
            manager.notify(that);
        }).on('change', 'input', function(this: HTMLInputElement) {
            const val = this.value;
            that._value = typeof val === 'undefined' || val === '' ? undefined : EditorHelper.parseNumber(val);
            manager.notify(that);
        });
    }
}

class EditorSizeElement implements IEditorElement {
  
    constructor(
        value: any = '',
        public label?: string,
    ) {
        this.format(value);
    }

    private _value?: number;
    private _unit = 'px';
    private element: JQuery<HTMLElement>;

    public set value(arg: any) {
        this.format(arg);
        if (!this.element) {
            return;
        }
        this.element.find('.control-body .number-control-container input').val(typeof this._value === 'undefined' ? '' : this._value);
        this.element.find('.control-body .extra-control').val(this._unit);
    }

    public get value(): any {
        return {value: this._value, unit: this._unit};
    }

    private format(val: any) {
        if (!val) {
            this._value = undefined;
            return;
        }
        if (typeof val === 'object') {
            if (val.value || val.unit) {
                this._value = val.value;
                this._unit = val.unit;
            }
            return;
        }
        val = val.toString().trim();
        const match = val.match(/[\d\.]+/);
        if (!match) {
            this._value = undefined;
            return;
        }
        this._value = EditorHelper.parseNumber(match[0]);
        const unit = val.substring(match.index + match[0].length);
        if (unit) {
            this._unit = unit;
        }
    }

    public render(): string {
        const input = EditorNumberControl.numberInput(typeof this._value === 'undefined' ? '' : this._value);
        const core = this.label ? `<div class="control-body">
        <label for="">${this.label}</label>
        ${input}
        <input class="extra-control" value="${this._unit}" spellcheck="false">
    </div>` : `<div class="control-body">
    <input class="extra-control" value="${this._unit}" spellcheck="false">
    ${input}
</div>`;
        return `<div class="select-with-control">` + core + EditorSelectControl.selectOptionBar(['px', 'em', 'rem', 'vh', 'vw', '%', 'auto', 'none']) + '</div>';
    }
    public ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup) {
        const that = this;
        this.element = box;
        box.on('change', '.control-body .number-control-container input', function(this: HTMLInputElement) {
            const val = this.value;
            that._value = typeof val === 'undefined' || val === '' ? undefined : EditorHelper.parseNumber(val);
            manager.notify(that);
            // let target = $this.parent();
            // if (!target.hasClass('.control-inline-group')) {
            //     target = target.closest('.control-line-group');
            // }
            // target.trigger(EditorEventInputChange, $this.val());
        })
        .on('click', '.control-body .extra-control', function() {
            $(this).closest('.select-with-control').toggleClass('select-focus');
        }).on('select:change', '.select-option-bar', function(_, ele: HTMLDivElement) {
            const $this = $(ele);
            that._unit = $this.text();
            $this.closest('.select-with-control').removeClass('select-focus').find('.control-body .extra-control').val(that._unit);
            manager.notify(that);
        }).on('click', '.select-option-bar .option-item', function() {
            const $this = $(this);
            $this.addClass('selected').siblings().removeClass('selected');
            $this.closest('.select-option-bar').trigger('select:change', this);
        });
    }
}

class EditorBoundElement implements IEditorElement, IEditorInputGroup {
  
    constructor(
        private _value: any[] = [],
        public columns: string[] = ['上', '右', '下', '左']
    ) {

    }
    private items: EditorSizeElement[] = [];
    private manager: IEditorInputGroup;

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        this.items.forEach((item, i) => {
            item.value = arg[i];
        });
    }

    public get value(): any[] {
        return this._value;
    }
    
    render(): string {
        this.items = this.columns.map((label, i) => {
            return new EditorSizeElement(this.value[i], label);
        });
        const html = this.items.map(i => i.render()).join('');
        return `<div class="control-row">${html}</div>`;
    }
    public ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup) {
        this.manager = manager;
        const items = box.find('.select-with-control');
        this.items.forEach((item, i) => {
            item.ready(items.eq(i), this);
        });
    }

    notify(control: IEditorElement): void {
        this._value = this.items.map(i => i.value);
        this.manager.notify(this);
    }
}

class EditorCheckElement implements IEditorElement {
  
    constructor(
        private _value: any,
        public items: IItem[],
        public multiple = false
    ) {

    }

    private element?: JQuery<HTMLElement>;

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (!this.element) {
            return;
        }
        const selected = this.selectedIndex;
        this.element.children('.radio-control-item').each(function(i) {
            $(this).toggleClass('checked', selected.indexOf(i) >= 0);
        });
    }

    public get value() {
        return this._value;
    }

    public get selectedIndex(): number[] {
        const items: number[] = [];
        for (let i = 0; i < this.items.length; i++) {
            const item = this.items[i];
            if (this.isSelected(item)) {
                items.push(i);
            }
        }
        return items;
    }

    private isSelected(item: IItem): boolean {
        if (!this.multiple) {
            return item.value === this._value;
        }
        if (item.value === this._value) {
            return true;
        }
        if (this._value instanceof Array) {
            return this._value.indexOf(item.value) >= 0;
        }
        return false;
    }

    render(): string {
        return EditorHtmlHelper.join('<div class="control-row">', ...this.items.map((item, j) => {
            const chk = this.isSelected(item) ? ' checked' : '';
            return `<span class="radio-control-item${chk}" data-value="${item.value}">${item.name}</span>`;
        }), '</div>');
    }

    public ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup) {
        this.element = box;
        const that = this;
        box.on('click', '.radio-control-item', function() {
            const $this = $(this);
            const isChecked = !$this.hasClass('checked');
            if (!that.multiple && !isChecked) {
                return;
            }
            $this.toggleClass('checked', isChecked);
            if (!that.multiple && isChecked) {
                $this.siblings().removeClass('checked');
            }
            that.tapItem($this.index());
            manager.notify(that);
            // const target = $this.closest('.control-row');
            // const group = target.closest('.control-line-group');
            // group.trigger(EditorEventInputChange, val);
            // if (target.data('tab')) {
            //     group.trigger(EditorEventTabToggle, [target.data('tab'), val]);
            // }
        });
    }

    private tapItem(i: number) {
        const val = this.items[i].value;
        if (!this.multiple) {
            this._value = val;
            return;
        }
        if (!this._value) {
            this._value = [val];
            return;
        }
        const j = this._value.indexOf(val);
        if (j >= 0) {
            this._value.splice(j, 1);
            return;
        }
        this._value.push(val);
    }
}

class EditorSelectElement implements IEditorElement {

    constructor(
        private _value?: string,
        public items: any[] = [],
        public editable = false,
        public searchable = false,
        public arrow = 'fa-angle-down',
        public multiple = false,
        public placeholder?: string
    ) {

    }

    private element: JQuery<HTMLElement>;

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (!this.element) {
            return;
        }
        if (this.element[0] instanceof HTMLInputElement) {
            this.element.val(arg);
        } else {
            this.element.text(arg);
        }
    }

    public get value() {
        return this._value;
    }


    public render(): string {
        const val = !this.multiple ? this.value : '';
        let body = this.editable ? `<input type="text" class="input-body"  value="${val}">` : `<span class="input-body">${val}</span>`;
        if (this.multiple) {
            body = `<div class="selected-container"></div>` + body;
        } else {
            body += `<div class="input-clear">
            <i class="fa fa-close"></i>
        </div>`;
        }
        return EditorHtmlHelper.join(`<div class="select-control-container">`, `<div class="select-input-container">
            ${body}
            <div class="input-arrow">
                <i class="fa ${this.arrow}"></i>
            </div>
        </div>`, EditorSelectControl.selectOptionBar(this.items, this.value, this.searchable), `</div>`);
    }

    public ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup) {
        const that = this;
        this.element = box.find('.input-body');
        box.on('change', '.input-body', function() {
            const $this = $(this);
            const val = this instanceof HTMLInputElement ? this.value : $this.text();
            $this.next('.input-clear').toggle(!!val);
            $this.closest('.select-control-container').find('.select-option-bar .option-item').each(function() {
                const that = $(this);
                that.toggleClass('selected', val === that.data('value'));
            });
            that.value = val;
            manager.notify(that);
            // $this.closest('.control-line-group').trigger(EditorEventInputChange, val);
        }).on('click', '.selected-container .item-close', function() {
            $(this).closest('.selected-item').remove();
        }).on('click', '.input-clear', function() {
            const target = $(this).prev('.input-body');
            if (target[0] instanceof HTMLInputElement) {
                target.val('');
            } else {
                target.text('');
            }
            target.trigger('change');
        }).on('click', '.input-arrow', function() {
            $(this).closest('.select-control-container').toggleClass('select-focus');
        }).on('select:change', '.select-option-bar', function(_, ele: HTMLDivElement) {
            const $this = $(ele);
            const control = $this.closest('.select-control-container').removeClass('select-focus');
            const val = $this.data('value');
            if (that.multiple) {
                control.find('.selected-container').append(EditorSelectElement.multipleSelectedItem(val));
                return;
            }
            const target = control.find('.input-body');
            if (target[0] instanceof HTMLInputElement) {
                target.val(val);
            } else {
                target.text(val);
            }
            target.trigger('change');
        }).on('click', '.select-option-bar .option-item', function() {
            const $this = $(this);
            $this.addClass('selected').siblings().removeClass('selected');
            $this.closest('.select-option-bar').trigger('select:change', this);
        });
    }

    private static multipleSelectedItem(text: string): string {
        return `<div class="selected-item"><span class="item-close">&times;</span><div class="item-label">${text}</div></div>`;
    }
}

class EditorInputElement implements IEditorElement {
    constructor(
        public shimmed: string,
        public label: string,
        private inline = true,
    ) {
    }

    private element: JQuery<HTMLElement>;

    render(): string {
        return EditorInputElement.inputGroup(this as any, '', this.inline);
    }

    

    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        box.on('click', '.control-updated-tag', function() {
            const $this = $(this);
            $this.next().next().trigger(EditorEventInputReset).next('.control-popup').trigger(EditorEventInputReset);
            $this.addClass('hidden');
        });
    }

    /**
     * 生成普通的标签加输入组合
     * @param control 
     * @param body 
     * @param inline 
     * @returns 
     */
    public static inputGroup(control: IEditorInput, body: string, inline = true) {
        const inner = `<label for="${control.shimmed}">${control.label}</label>
        ${body}`;
        if (!inline) {
            return this.inputOutline(control, inner);
        }
        let cls = !control.label ? ' control-inline-not-label' : '';
        if (body.indexOf('control-popup-target') > 0) {
            cls += ' control-inline-popup-group';
        }
        return this.inputOutline(control, `<div class="control-inline-group${cls}">
        ${inner}
    </div>`);
    }

    /**
     * 生成标签+滑动条+输入的组合
     * @param control 
     * @param body 
     * @param bottom 
     * @returns 
     */
    public static inputSideGroup(control: IEditorInput, body: string, bottom: string = '') {
        return this.inputOutline(control, `<div class="control-inline-group">
        <div class="side-label"><label for="${control.shimmed}">${control.label}</label>${bottom}</div>
        ${body}
    </div>`);
    }

    private static inputOutline(control: IEditorInput, body: string) {
        const hidden = typeof control !== 'object' || !control.isUpdated ? ' hidden' : '';
        return `<div ${EditorHtmlHelper.shimmed(control.shimmed)} class="control-line-group"><i class="control-updated-tag${hidden}"></i>
        ${body}
        ${this.inputTooltip(control.tooltip)}
        </div>`;
    }

    private static inputTooltip(tooltip?: string): string {
        if (!tooltip) {
            return '';
        }
        return `<div class="control-tooltip">${tooltip}</div>`;
    }
}
