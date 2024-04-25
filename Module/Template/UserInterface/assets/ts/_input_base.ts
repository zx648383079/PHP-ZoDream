
class EditorTextControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    label?: string;
    private items = [];
    private _value = '';
    private control: EditorSelectElement;
    private element: JQuery<HTMLElement>;
    private manager: IEditorInputGroup;

    constructor(
        input: IVisualInput
    ) {
        if (input.items) {
            this.items = input.items;
        }
    }

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = typeof arg === 'undefined' ? '' : arg;
        if (this.control) {
            this.control.value = this._value;
        } else if (this.element) {
            this.element.find('input').val(this._value);
        }
    }

    public get value(): string {
        return this._value;
    }

    get isUpdated(): boolean {
        return !!this._value;
    }

    reset(): void {
        this.value = '';
    }


    render(): string {
        let html: string;
        if (this.items.length > 0) {
            this.control = new EditorSelectElement(this.value, this.items, true, false, 'fa-bolt');
            html = this.control.render();
        } else {
            html = `<input class="form-control" value="${this.value}">`;
        }
        return EditorInputElement.inputGroup(this, html, false);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.manager = manager;
        this.element = box;
        if (this.control) {
            this.control.ready(box.find('.select-control-container'), this);
            return;
        }
        const that = this;
        box.on('change', 'input', function() {
            that._value = this.value;
            manager.notify(that);
        });
    }

    notify(control: IEditorElement): void {
        this._value = this.control.value;
        this.element.trigger(EditorEventInputChange);
        this.manager.notify(this);
    }
}

class EditorTextAlignControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    label?: string;
    private _value?: any;
    private control: EditorCheckElement;
    private manager: IEditorInputGroup;
    private element: JQuery<HTMLElement>;

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (this.control) {
            this.control.value = this._value;
        }
    }

    public get value(): string {
        return this._value;
    }

    get isUpdated(): boolean {
        return !!this.value;
    }

    reset(): void {
        this.value = '';
    }
    render(): string {
        this.control = EditorAlignControl.horizontalAlign(this.value, true)
        return EditorInputElement.inputGroup(this, this.control.render(), false);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        this.manager = manager;
        this.control.ready(box.find('.control-row'), this);
    }

    notify(control: IEditorElement): void {
        this._value = this.control.value;
        this.element.trigger(EditorEventInputChange);
        this.manager.notify(this);
    }
}


class EditorRangeControl implements IEditorInput {
    shimmed?: string;
    name: string;
    label?: string;
    private _value?: number;
    private element: JQuery<HTMLElement>;
    private default: number;

    constructor(
        private input: IVisualInput
    ) {
        this.default = this.input.default ?? this.input.min;
    }

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (this.element) {
            this.element.find('select').val(this.value);
        }
    }

    public get value(): number {
        return this._value;
    }

    get isUpdated(): boolean {
        return this.value && this.value !== this.default;
    }


    reset(): void {
        this.value = this.default;
    }

    render(): string {
        const items = [];
        for (let i = this.input.min; i <= this.input.max; i+= this.input.step) {
            items.push({name: i, value: i});
        }
        return EditorInputElement.inputGroup(this, EditorSelectControl.selectControl(this.shimmed, this.name, items, this.value, this.input));
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        const that = this;
        box.on('change', 'select', function() {
            that._value = $(this).val();
            manager.notify(this);
            box.trigger(EditorEventInputChange);
        });
    }
}

class EditorHtmlControl implements IEditorInput {
    shimmed?: string;
    name: string;
    label?: string;
    private _value?: string;
    private element: ZreEditor.EditorApp;

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = typeof arg === 'undefined' ? '' : arg;
        if (this.element) {
            this.element.container.value = this._value;
        }
    }

    public get value(): string {
        return this._value;
    }

    get isUpdated(): boolean {
        return !!this.value;
    }

    reset(): void {
        this._value = '';
    }
    render(): string {
        return EditorInputElement.inputGroup(this, `<textarea>${this.value}</textarea>`, false);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box.find('textarea').editor();
        this.element.container.on('change', () => {
            this._value = this.element.container.value;
        });
    }
}

class EditorSelectControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    label?: string;
    private _value?: string;
    private control: EditorSelectElement;
    private manager: IEditorInputGroup;
    private element: JQuery<HTMLElement>;
    
    constructor(
        private input: IVisualInput
    ) {
    }

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (this.control) {
            this.control.value = this.value;
        } else if (this.element) {
            this.element.find('select').val(this.value);
        }
    }

    public get value(): string {
        return this._value;
    }

    get isUpdated(): boolean {
        return this.value && this.value !== this.input.items[0].value;
    }

    reset(): void {
        this.value = this.input.items[0].value;
    }
    
    render(): string {
        let html: string;
        if (!this.input.search && !this.input.multiple && this.input.items) {
            html = EditorSelectControl.selectControl(this.shimmed, this.input.name, this.input.items, this.input.value, this.input);
        } else {
            this.control = new EditorSelectElement(this.value, this.input.items, false, this.input.search, undefined, this.input.multiple);
            html = this.control.render();
        }
        return EditorInputElement.inputGroup(this, html, !this.input.multiple);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.manager = manager;
        this.element = box;
        if (this.control) {
            this.control.ready(box.find('.select-control-container'), this);
            return;
        }
        const that = this;
        box.on('change', 'select', function() {
            that._value = $(this).val();
            manager.notify(this);
            box.trigger(EditorEventInputChange);
        });
    }

    notify(control: IEditorElement): void {
        this._value = this.control.value;
        this.manager.notify(this);
        this.element.trigger(EditorEventInputChange);
    }

    /**
     * 生成原生select
     * @param id 
     * @param name 
     * @param items 
     * @param val 
     * @returns 
     */
    public static selectControl(id: string, name: string, items: IItem[], val?: any, input?: IVisualInput) {
        return `<select class="form-control" name="${name}" id="${id}"${EditorHtmlHelper.renderAttribute(input)}>
        ${this.selectOption(items, val)}
        </select>`;
    }

    private static selectOption(items: IItem[], selected?: any) {
        return items.map(item => {
            const sel = selected == item.value ? ' selected' : '';
            return `<option value="${item.value}"${sel}>${item.name}</option>`;
        }).join('');
    }


    public static selectOptionBar(items: string[]|IItem[], selected?: any, searchable = false) {
        let option = searchable ? `<div class="search-option-item">
        <input type="text">
        <i class="fa fa-search"></i>
    </div>` : '';
        option += items.map(item => {
            const name = typeof item === 'object' ? item.name : item;
            const value = typeof item === 'object' ? item.value : item;
            const sel = value === selected ? ' selected' : '';
            return `<div class="option-item${sel}" data-value="${value}">${name}</div>`;
        }).join('');
        return `<div class="select-option-bar">${option}</div>`
    }
}


class EditorImageControl implements IEditorInput {
    shimmed?: string;
    name: string;
    label?: string;
    private _value?: string;
    private element: JQuery<HTMLElement>;

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg ? arg : '';
        if (this.element) {
            this.element.find('.control-body').html(!this.value ? EditorImageControl.imageUpload(this.shimmed) : EditorImageControl.imageItem(this.value));
        }
    }

    public get value(): string {
        return this._value;
    }
    get isUpdated(): boolean {
        return !!this.value;
    }

    reset(): void {
        this.value = '';
    }
    render(): string {
        const html = !this.value ? EditorImageControl.imageUpload(this.shimmed) : EditorImageControl.imageItem(this.value);
        return EditorInputElement.inputGroup(this, `<div class="control-body">${html}</div>`, false);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        const that = this;
        box.on('change', '.drag-control-container input[type=file]', function(this: HTMLInputElement) {
            const target = $(this).closest('.drag-control-container');
            target.addClass('is-uploading');
            EditorImageControl.upload(this.files[0], url => {
                if (!url) {
                    target.removeClass('is-uploading');
                    return;
                }
                that._value = url;
                box.find('.control-body').html(EditorImageControl.imageItem(url));
                box.trigger(EditorEventInputChange);
                manager.notify(that);
            });
        }).on('click', '.image-control-item .fa-trash', function() {
            that.reset();
            box.trigger(EditorEventInputChange);
            manager.notify(that);
        });
    }

    public static upload(file: File, cb: (url?: string) => void) {
        const form = new FormData();
        form.append('upfile', file);
        $.ajax({
            url: UPLOAD_URI,
            type: 'POST',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            success: data => {
                cb(data && data.state === 'SUCCESS' ? data.url : undefined);
            },
            error: _ => {
                cb();
            }
        });
    }

    public static imageUpload(id: string): string {
        return `<div class="drag-control-container"><label for="${id}">
            拖放文件
            <p>(或点击)</p>
            <input type="file" id="${id}" accept="image/*">
            </label>
            <div class="loading">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
            `;
    }

    public static imageItem(value: string): string {
        return `<div class="image-control-item">
        <div class="control-body">
            <img src="${value}">
        </div>
        <div class="control-action">
            <i class="fa fa-edit"></i>
            <i class="fa fa-trash"></i>
        </div>
    </div>`;
    }
}

class EditorSwitchControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name?: string;
    label?: string;
    private _value?: number;
    private target: EditorSwitchElement = new EditorSwitchElement;
    private manager: IEditorInputGroup;
    private element?: JQuery<HTMLElement>;
    
    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = EditorHelper.parseNumber(arg);
        if (this.target) {
            this.target.value = this._value;
        }
    }

    public get value(): number {
        return this._value;
    }

    get isUpdated(): boolean {
        return this.value > 0;
    }

    reset(): void {
        this.value = 0;
    }
    render(): string {
        this.target.value = EditorHelper.parseNumber(this.value);
        return EditorInputElement.inputGroup(this, this.target.render());
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.manager = manager;
        this.element = box;
        this.target.ready(box.children('.switch-input'), this);
    }
    notify(control: IEditorInput): void {
        this._value = control.value;
        this.element.trigger(EditorEventInputChange);
        this.manager.notify(this);
    }
}



class EditorTextareaControl implements IEditorInput {
    shimmed?: string;
    name: string;
    label?: string;
    private element: JQuery<HTMLElement>;
    private _value = '';

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = typeof arg === 'undefined' ? '' : arg;
        if (this.element) {
            this.element.find('textarea').val(this.value);
        }
    }

    public get value(): string {
        return this._value;
    }

    get isUpdated(): boolean {
        return !!this.value;
    }

    reset(): void {
        this.value = '';
    }
    render(): string {
        return EditorInputElement.inputGroup(this, `<textarea class="form-control" id="${this.shimmed}" name="${this.name}">${this.value}</textarea>`, false);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        const that = this;
        box.on('change', 'textarea', function() {
            that._value = this.value;
            manager.notify(that);
        });
    }
}

class EditorNumberControl implements IEditorInput {
    shimmed?: string;
    name: string;
    label?: string;
    private _value = 0;
    private element: JQuery<HTMLElement>;

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = EditorHelper.parseNumber(arg);
        if (this.element) {
            this.element.find('input').val(this.value);
        }
    }

    public get value(): number {
        return this._value;
    }

    get isUpdated(): boolean {
        return !!this.value;
    }
    

    reset(): void {
        this.value = 0;
    }
    render(): string {
        return EditorInputElement.inputSideGroup(this, EditorNumberControl.numberInput(this.value), `<input type="range" value="${EditorHelper.parseNumber(this.value)}">`);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        const that = this;
        box.on('change', 'input', function() {
            that.value = this.value;
            manager.notify(that);
        });
    }

    public static numberInput(val: number|string = ''): string {
        return `<div class="number-control-container">
        <input type="text" value="${val}">
        <div class="control-action-bar">
            <i class="fa fa-angle-up action-plus"></i>
            <i class="fa fa-angle-down action-minus"></i>
        </div>
    </div>`;
    }
}

class EditorSizeControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    label?: string;

    private _value?: any;
    private control = new EditorSizeElement;
    private manager: IEditorInputGroup;
    private element: JQuery<HTMLElement>;

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this.control.value = arg;
        this._value = this.control.value;
        if (this.element) {
            this.element.find('.side-label input').val(this._value.value);
        }
    }

    public get value(): any {
        return this._value;
    }

    get isUpdated(): boolean {
        return this._value && this._value.value;
    }

    reset(): void {
        this.value = undefined;
    }
    render(): string {
        return EditorInputElement.inputSideGroup(this, this.control.render(), `<input type="range" value="${EditorHelper.parseNumber(this.control.value.value)}">`);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        const that = this;
        this.element = box;
        this.manager = manager;
        this.control.ready(box.find('.select-with-control'), this);
        box.on('change', '.side-label input', function() {
            const val = that.value;
            val.value = EditorHelper.parseNumber(this.value);
            that.control.value = that._value = val;
            box.trigger(EditorEventInputChange);
            manager.notify(that);
        })
    }

    notify(control: IEditorElement): void {
        this._value = this.control.value;
        this.element.find('.side-label input').val(this._value.value);
        this.manager.notify(this);
        this.element.trigger(EditorEventInputChange);
    }
}

class EditorRadioControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    label?: string;
    private _value?: any;
    private items: IItem[] = [];
    private control: EditorCheckElement;
    private manager: IEditorInputGroup;

    get isUpdated(): boolean {
        return !this._value;
    }

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (this.control) {
            this.control.value = arg;
        }
    }

    public get value(): string {
        return this._value;
    }

    constructor(
        input: IVisualInput
    ) {
        this.items = input.items;
    }

    reset(): void {
        this.value = undefined;
    }
    render(): string {
        this.control = new EditorCheckElement(this.value, this.items);
        return EditorInputElement.inputGroup(this, this.control.render(), false);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.manager = manager;
    }

    notify(control: IEditorElement): void {
        this._value = this.control.value;
        this.manager.notify(this);
    }
}

class EditorBoundControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    label?: string;
    private columns: string[] = ['上', '右', '下', '左'];
    private control = new EditorBoundElement;
    private manager: IEditorInputGroup;
    private element: JQuery<HTMLElement>;

    public set value(arg: any) {
        this.control.value = arg;
    }

    public get value(): any[] {
        return this.control.value;
    }

    get isUpdated(): boolean {
        return this.value && this.value.filter(i => !!i.value).length > 0;
    }

    constructor(
        input: IVisualInput
    ) {
        if (input.items) {
            this.columns = input.items;
        }
    }

    reset(): void {
        this.value = [];
    }
    
    render(): string {
        this.control.columns = this.columns;
        return EditorInputElement.inputGroup(this, this.control.render()
        , false);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.manager = manager;
        this.element = box;
        this.control.ready(box.find('.control-row'), this);
    }

    notify(control: IEditorElement): void {
        this.manager.notify(this);
        this.element.trigger(EditorEventInputChange);
    }
}
