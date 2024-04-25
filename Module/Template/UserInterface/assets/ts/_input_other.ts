class EditorMaskControl implements IEditorInput {
    shimmed?: string;
    name: string;
    label?: string;

    private _value?: string;
    private element: JQuery<HTMLElement>;
    private items: string[] = [
        'inset(10%)',
        'circle(45% at 50% 50%)',
        'ellipse(30% 45% at 50% 50%)',
        'polygon(50% 0%, 0% 100%, 100% 100%)',
        'polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%)',
        'polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%)',
        'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)',
        'polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%)',
        'polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%)',
    ];

    public set value(arg: string) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (this.element) {
            this.element.find('.clip-path-box .shape').removeClass('selected').eq(this.items.indexOf(arg)).addClass('selected');
        }
    }

    public get value() {
        return this._value;
    }

    get isUpdated(): boolean {
        return !!this._value;
    }

    reset(): void {
        this.value = undefined;
    }

    render(): string {
        const html = this.items.map(i => `<div class="shape" style="clip-path: ${i};"></div>`).join('');
        return `<div class="mark-container" ${EditorHtmlHelper.shimmed(this.shimmed)}>
        <div class="clip-path-box">
            ${html}
        </div>
        <button class="btn btn-primary">一键取消</button>
    </div>
    `;
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        const that = this;
        box.on('click', '.btn', function() {
            that.reset();
            manager.notify(that);
        }).on('click', '.clip-path-box .shape', function() {
            const $this = $(this);
            $this.addClass('selected').siblings().removeClass('selected');
            that._value = that.items[$this.index()];
            manager.notify(that);
        });
    }
}

class EditorPositionControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    label?: string;

    private _type = 'static';
    private _value = [];
    private control: EditorBoundElement = new EditorBoundElement;
    private manager: IEditorInputGroup;
    private element: JQuery<HTMLElement>;
    private typeItems: IItem[] = [
        {name: '无', value: 'static'},
        {name: '相对定位', value: 'relative'},
        {name: '绝对定位', value: 'absolute'},
        {name: '固定定位', value: 'fixed'},
    ];

    public set value(arg: any) {
        if (!arg) {
            this._type = 'static';
            this._value = [];
            return;
        }
        this._type = arg.type;
        this._value = arg.type === 'static' ? [] : arg.value;
        if (!this.element) {
            return;
        }
        this.element.find('select').val(this._type);
        this.element.next().toggleClass('hidden', this._type === this.typeItems[0].value);
        this.control.value = this._value;
    }

    public get value() {
        if (this._type === 'static') {
            return {type: this._type};
        }
        return {
            type: this._type,
            value: this._value
        };
    }

    get isUpdated(): boolean {
        return this._type !== this.typeItems[0].value;
    }

    reset(): void {
        this.value = {
            type: 'static',
        };
    }

   
    render(): string {
        const hidden = this._type !== this.typeItems[0].value ? '' : ' hidden';
        this.control.value = this._value;
        const bound = this.control.render();
        const select = EditorInputElement.inputGroup(this, EditorSelectControl.selectControl(this.shimmed, this.name + '[type]', this.typeItems, this._type));
        return  `<div ${EditorHtmlHelper.shimmed(this.shimmed)} class="position-container">
        ${select}
        <div class="${hidden}">${bound}</div>
    </div>`
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        const that = this;
        this.manager = manager;
        this.control.ready(box.find('.control-row'), this);
        this.element = box.find('.control-inline-group');
        box.on('change', 'select', function(this: HTMLSelectElement) {
            const $this = $(this);
            that._type = $this.val() as string;
            $this.closest('.control-inline-group').next().toggleClass('hidden', that._type === that.typeItems[0].value);
        });
    }

    notify(control: IEditorElement): void {
        this._value = this.control.value;
        this.manager.notify(this);
    }
}

class EditorGroupControl implements IEditorInput {
    shimmed?: string;
    label?: string;

    get isUpdated(): boolean {
        return false;
    }
   
    constructor(
        private input: IVisualInput
    ) {
    }

    reset(): void {
    }
    render(): string {
        const children = EditorHtmlHelper.render(this.input.items);
        if (!this.label) {
            return `<div${EditorHtmlHelper.renderClass(this.input)}>${children}</div>`
        }
        return `<div ${EditorHtmlHelper.shimmed(this.shimmed)} class="expand-box">
        <div class="expand-header">${this.label}<span class="fa fa-chevron-right"></span></div>
        <div class="expand-body">
        ${children}
        </div>
    </div>`;
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        EditorHtmlHelper.readyControl(box.find('.expand-body'), manager);
    }
}

class EditorAlignControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    label?: string;

    private manager: IEditorInputGroup;
    private items: EditorCheckElement[] = [
        EditorAlignControl.horizontalAlign(),
        EditorAlignControl.verticalAlign()
    ];

    public set value(arg: any) {
        this.items.forEach((item, i) => {
            item.value = arg ? arg[i] : undefined;
        });
    }

    public get value() {
        return this.items.map(i => i.value);
    }
    
    get isUpdated(): boolean {
        for (const item of this.items) {
            if (item.value) {
                return true;
            }
        }
        return false;
    }

    reset(): void {
        this.value = [];
    }

    render(): string {
        return EditorHtmlHelper.join('<div ', EditorHtmlHelper.shimmed(this.shimmed) ,'>', this.items.map(i => i.render()).join(''), '</div>');
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.manager = manager;
        const nodes = box.children('.control-row');
        this.items.forEach((item, i) => {
            item.ready(nodes.eq(i), this);
        });
    }

    notify(control: IEditorElement): void {
        this.manager.notify(this);
    }

    public static horizontalAlign(value?: any, isTextAlign = false) {
        const items = [
            {name: '<i class="fa fa-align-left" title="左对齐"></i>', value: 'left'},
            {name: '<i class="fa fa-align-center" title="水平居中"></i>', value: 'center'},
            {name: '<i class="fa fa-align-right" title="右对齐"></i>', value: 'right'},
        ];
        if (!isTextAlign) {
            items.push({name: '<i class="fa fa-align-justify" title="水平等间距"></i>', value: 'stretch'});
        }
        return new EditorCheckElement(value, items);
    }

    public static verticalAlign(value?: any) {
        return new EditorCheckElement(value, [
            {name: '<i class="fa fa-align-left fa-vertical" title="上对齐"></i>', value: 'top'},
            {name: '<i class="fa fa-align-center fa-vertical" title="垂直居中"></i>', value: 'center'},
            {name: '<i class="fa fa-align-right fa-vertical" title="下对齐"></i>', value: 'bottom'},
            {name: '<i class="fa fa-align-justify fa-vertical" title="垂直等间距"></i>', value: 'stretch'},
        ]);
    }
}

class EditorAnimationControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    label?: string;

    private items: IEditorInput[] = [
        EditorHtmlHelper.renderControl({
            label: '动效',
            type: 'select',
            name: 'type',
            items: [
                { value: '', name: '无' },
                { value: 'bounce', name: '弹跳' },
                { value: 'fadeIn', name: '渐现' },
                { value: 'fadeOut', name: '渐出' },
                { value: 'flash', name: '闪烁' },
                { value: 'pulse', name: '跳动' },
                { value: 'rubberBand', name: '橡皮筋' },
                { value: 'shake', name: '抖动' },
                { value: 'swing', name: '摆动' },
                { value: 'tada', name: '哒嘟' },
                { value: 'wobble', name: '摇晃' },
                { value: 'jello', name: '扭曲抖动' },
                { value: 'bounceIn', name: '弹入' },
                { value: 'bounceInDown', name: '上弹入' },
                { value: 'bounceInLeft', name: '左弹入' },
                { value: 'bounceInRight', name: '右弹入' },
                { value: 'bounceInUp', name: '下弹入' },
                { value: 'flipInX', name: '水平翻转' },
                { value: 'flipInY', name: '垂直翻转' },
                { value: 'spinning', name: '旋转（顺时针）' },
                { value: 'spinning-reverse', name: '旋转（逆时针）' },
                { value: 'rotateIn', name: '旋入' },
                { value: 'rotateInDownLeft', name: '左下旋转' },
                { value: 'rotateInDownRight', name: '右下旋转' },
                { value: 'rotateInUpLeft', name: '左上旋转' },
                { value: 'rotateInUpRight', name: '右上旋转' },
                { value: 'slideInDown', name: '上滑入' },
                { value: 'slideInLeft', name: '左滑入' },
                { value: 'slideInRight', name: '右滑入' },
                { value: 'slideInUp', name: '下滑入' },
                { value: 'zoomIn', name: '逐渐放大' },
                { value: 'zoomInDown', name: '从下放大' },
                { value: 'zoomInLeft', name: '从左放大' },
                { value: 'zoomInRight', name: '从右放大' },
                { value: 'zoomInUp', name: '从上放大' },
                { value: 'rollIn', name: '滚入' },
                { value: 'lightSpeedIn', name: '闪入' },
            ]
        }),
        EditorHtmlHelper.renderControl({
            label: '延时',
            type: 'select',
            name: 'delay',
            items: [
                { value: '', name: '无' },
                { value: '0.1s', name: '100ms' },
                { value: '0.2s', name: '200ms' },
                { value: '0.3s', name: '300ms' },
                { value: '0.5s', name: '500ms' },
                { value: '1s', name: '1s' },
                { value: '2s', name: '2s' },
                { value: '3s', name: '3s' },
                { value: '4s', name: '4s' },
                { value: '5s', name: '5s' },
                { value: '6s', name: '6s' },
                { value: '7s', name: '7s' },
                { value: '8s', name: '8s' },
                { value: '9s', name: '9s' },
                { value: '10s', name: '10s' },
            ]
        }),
        EditorHtmlHelper.renderControl({
            label: '时长',
            type: 'select',
            name: 'duration',
            items: [
                { value: '0.25s', name: '250ms' },
                { value: '0.5s', name: '500ms' },
                { value: '0.75s', name: '750ms' },
                { value: '1s', name: '1s' },
                { value: '2s', name: '2s' },
                { value: '3s', name: '3s' },
                { value: '4s', name: '4s' },
                { value: '5s', name: '5s' },
            ]
        }),
        EditorHtmlHelper.renderControl({
            label: '重复',
            type: 'select',
            name: 'count',
            items: [
                { value: '1', name: '1' },
                { value: '2', name: '2' },
                { value: '3', name: '3' },
                { value: '4', name: '4' },
                { value: '5', name: '5' },
                { value: '6', name: '6' },
                { value: '7', name: '7' },
                { value: '8', name: '8' },
                { value: '9', name: '9' },
                { value: '10', name: '10' },
                { value: 'infinite', name: '无限循环' },
            ]
        }),
        EditorHtmlHelper.renderControl({
            label: '缓动函数',
            type: 'select',
            name: 'func',
            items: [
                { value: 'linear', name: '线性' },
                { value: 'ease', name: 'ease' },
                { value: 'ease-in', name: 'ease-in' },
                { value: 'ease-out', name: 'ease-out' },
                { value: 'ease-in-out', name: 'ease-in-out' },
            ]
        }),
    ];
    private element: JQuery<HTMLElement>;
    private manager: IEditorInputGroup;

    public set value(arg: any) {
        this.items.forEach(item => {
            if (!arg) {
                item.reset();
                return;
            }
            item.value = item[item.name];
        });
    }

    public get value(): any {
        const data = {};
        for (const item of this.items) {
            data[item.name] = item.value;
        }
        return data;
    }

    get isUpdated(): boolean {
        for (const item of this.items) {
            if (item.isUpdated) {
                return true;
            }
        }
        return false;
    }

    reset(): void {
        this.value = undefined;
    }

    render(): string {
        const html = this.items.map(i => i.render()).join('');
        return `<div class="animation-container" ${EditorHtmlHelper.shimmed(this.shimmed)}>
        <div class="effect-show">效果展示</div>
       <div class="control-body">${html}</div>
    </div>`;
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        this.manager = manager;
        const items = box.find('.control-body').children();
        this.items.forEach((item, i) => {
            item.ready(items.eq(i), this);
        });
    }

    notify(control: IEditorElement): void {
        this.manager.notify(this);
    }
}
