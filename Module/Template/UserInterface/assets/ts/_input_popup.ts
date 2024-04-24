class EditorColorControl implements IEditorInput {
    shimmed?: string;
    name: string;
    label?: string;

    private _value?: string;
    private element: JQuery<HTMLElement>;
    get isUpdated(): boolean {
        return !!this.value;
    }

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (!this.element) {
            return;
        }
        this.setColor(this._value);
    }

    public get value(): string {
        return this._value;
    }

    reset(): void {
        this.value = '';
    }

    render(): string {
        // return `<input type="color" class="form-control-color" id="${id}" name="${name}">`;
        const html = `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
    </div>
    <div class="control-popup" data-popup="color">
        <div class="popup-action">
            <div class="btn btn-danger">清除</div>
        </div>
        <div class="control-line-group mt-0">
            <label for="">Hue</label>
            <input type="range" class="hue-bar" min="0" max="360" step="1" value="0">
        </div>
        <div class="control-line-group mt-0">
            <label for="">Saturation</label>
            <input type="range" class="saturation-bar" min="0" max="100" step="1" value="0">
        </div>
        <div class="control-line-group mt-0">
            <label for="">Lightness</label>
            <input type="range" class="lightness-bar" min="0" max="100" step="1" value="0">
        </div>
        <div class="control-line-group alpha-ouline-bar mt-0">
            <label for="">Transparency</label>
            <input type="range" class="alpha-bar" min="0" max="100" step="1" value="1">
        </div>
    
        <div class="control-row tab-bar-target active">
            <div class="control-half-group">
                <input type="text">
                <label for="">HEX</label>
            </div>
        </div>
        <div class="control-row tab-bar-target">
            <div class="control-half-group">
                <input type="number">
                <label for="">R</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">G</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">B</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">A</label>
            </div>
        </div>
        <div class="control-row tab-bar-target">
            <div class="control-half-group">
                <input type="number">
                <label for="">H</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">S</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">L</label>
            </div>
            <div class="control-half-group">
                <input type="number">
                <label for="">A</label>
            </div>
        </div>
        <div class="tab-bar">
            <a class="item active">HEX</a>
            <a class="item">RGB</a>
            <a class="item">HSL</a>
        </div>
    </div>`;
        return EditorHtmlHelper.input(this, this.label, html);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        const that = this;
        this.element = box;
        if (this.value) {
            this.setColor(this.value);
        }
        box.on('change', '.hue-bar,.saturation-bar,.lightness-bar,.alpha-bar', function() {
            const hsl = that.getColor();
            that.applyColor(that.getColor());
            that._value = EditorColorControl.rgbaToHex(EditorColorControl.hslToRgba(hsl));
            box.trigger(EditorEventInputChange);
            manager.notify(that);
        });
    }

    private setColor(color?: string) {
        if (!this.element) {
            return;
        }
        const hCtl = this.element.find('.hue-bar');
        const sCtl = this.element.find('.saturation-bar');
        const lCtl = this.element.find('.lightness-bar');
        const tCtl = this.element.find('.alpha-bar');
        const format = EditorColorControl.colorToRgba(color);
        const hsl = EditorColorControl.rgbaToHsl(format);
        hCtl.val(hsl.h);
        sCtl.val(hsl.s);
        lCtl.val(hsl.l);
        tCtl.val(hsl.a * 100);
        this.applyColor(hsl);
    }

    private applyColor(hsl: IHslColor) {
        if (!this.element) {
            return;
        }
        const rgba = EditorColorControl.hslToRgba(hsl);
        const format = `rgba(${rgba.r}, ${rgba.g}, ${rgba.b}, ${rgba.a})`;
        this.element.find('.saturation-bar').css('background-image', `linear-gradient(to right, gray, ${format})`);
        this.element.find('.lightness-bar').css('background-image', `linear-gradient(to right, black, ${format})`);
        this.element.find('.alpha-bar').css('background-image', `linear-gradient(to right, transparent, ${format})`);
        this.element.find('.tab-bar-target').each(function(i) {
            const that = $(this);
            if (i === 0) {
                that.find('input').val(EditorColorControl.rgbaToHex(rgba));
            } else {
                const items = i === 1 ? [rgba.r, rgba.g, rgba.b, rgba.a] : [hsl.h, hsl.s, hsl.l, hsl.a];
                that.find('input').each(function(this: HTMLInputElement, j) {
                    this.value = items[j].toString();
                });
            }
        });
    }

    private getColor(): IHslColor {
        if (!this.element) {
            return {h: 0, s: 0, l: 0, a: 0};
        }
        const hCtl = this.element.find('.hue-bar');
        const sCtl = this.element.find('.saturation-bar');
        const lCtl = this.element.find('.lightness-bar');
        const tCtl = this.element.find('.alpha-bar');
        return {h: toInt(hCtl.val()), s: toInt(sCtl.val()), l: toInt(lCtl.val()), a: toInt(tCtl.val()) / 100};
    }

    public static colorToRgba(color: string): IRgbaColor {
        const code = color.charAt(0);
        const matchFunc = (val: string): number[] => {
            return val.substring(val.indexOf('(') + 1, val.indexOf(')')).split(',').map(i => toFloat(i));
        };
        if (code === 'h') {
            const items = matchFunc(color);
            return this.hslToRgba({h: items[0], s: items[1], l: items[2], a: 1});
        } else if (code === 'r') {
            const items = matchFunc(color);
            if (items.length > 3) {
                return {r: items[0], g: items[1], b: items[2], a: items[3]};
            }
            return {r: items[0], g: items[1], b: items[2], a: 1};
        }
        return this.hexToRgba(color);
    }

    public static rgbaToHex(color: IRgbaColor): string {
        return ['#', color.r.toString(16), color.g.toString(16), color.b.toString(16), color.a >= 0 && color.a < 1 ? Math.round(color.a * 255).toString(16) : ''].join('');
    }

    public static hexToRgba(hex: string): IRgbaColor {
        if (hex.charAt(0) === '#') {
            hex = hex.substring(1);
        }
        let r: number, g: number, b: number = 0;
        let a: number = 1;
        const toHex = (val: string, isDouble = false) => {
            if (!val) {
                return 0;
            }
            const i = parseInt(val, 16);
            if (!isDouble) {
                return i;
            }
            return i * 17;
        };
        const step = hex.length <= 4 ? 1 : 2;
        r = toHex(hex.substring(0, step), step === 2);
        g = toHex(hex.substring(step, step * 2), step === 2);
        b = toHex(hex.substring(step * 2, step * 3), step === 2);
        if (hex.length > step * 3) {
            a = toHex(hex.substring(step * 3, step * 4), step === 2);
        }
        return { r, g, b, a };
    }

    public static rgbaToHsl(color: IRgbaColor): IHslColor {
        let r = color.r;
        let g = color.g;
        let b = color.b;
        r /= 255, g /= 255, b /= 255;
        let max = Math.max(r, g, b), min = Math.min(r, g, b);
        let h: number, s: number, l: number = (max + min) / 2;
        if (max == min) {
            h = s = 0; // achromatic
        } else {
            let d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                case g: h = (b - r) / d + 2; break;
                case b: h = (r - g) / d + 4; break;
            }
            h /= 6;
        }
        return { h, s, l, a: color.a};
    }

    public static hslToRgba(color: IHslColor): IRgbaColor {
        let h = color.h / 360, s = color.s / 100, l = color.l / 100;
        let r: number, g: number, b: number;
        if (s === 0) {
            r = g = b = l; // achromatic
        } else {
            const hue2rgb = (p: number, q: number, t: number) => {
                if (t < 0) t += 1;
                if (t > 1) t -= 1;
                if (t < 1 / 6) return p + (q - p) * 6 * t;
                if (t < 1 / 2) return q;
                if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
                return p;
            }
            let q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            let p = 2 * l - q;
            r = hue2rgb(p, q, h + 1 / 3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1 / 3);
        }
        return { r: Math.round(r * 255), g: Math.round(g * 255), b: Math.round(b * 255), a: color.a };
    }
}

class EditorBackgroundControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    label?: string;

    private manager: IEditorInputGroup;
    private element: JQuery<HTMLElement>;
    private items: IEditorInput[] = [
        EditorHtmlHelper.renderControl({
            label: '背景色',
            type: 'color',
            name: 'color'
        }),
        EditorHtmlHelper.renderControl({
            label: '背景图片',
            type: 'image',
            name: 'image'
        })
    ];

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
        const children = this.items.map(i => i.render()).join('');
        const html = `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
    </div>
    <div class="control-popup" data-popup="background">
        ${children}
    </div>
    `;
        return EditorHtmlHelper.input(this, this.label, html);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        this.manager = manager;
        const items = box.find('.control-popup').children();
        const that = this;
        this.items.forEach((item, i) => {
            item.ready(items.eq(i), that);
        });
    }

    notify(control: IEditorInput): void {
        this.manager.notify(this);
    }
}

class EditorBorderControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name?: string;
    label?: string;

    private items: IEditorInput[] = [
        EditorHtmlHelper.renderControl({
            label: 'Width',
            type: 'bound',
            name: 'width'
        }),
        EditorHtmlHelper.renderControl({
            label: 'Style',
            type: 'select',
            name: 'style',
            items: [
                {name: '无', value: ''},
                {name: '横线', value: 'solid'},
                {name: '点线', value: 'dotted'},
                {name: '虚线', value: 'double'},
            ]
        }),
        EditorHtmlHelper.renderControl({
            label: 'Color',
            type: 'color',
            name: 'color'
        }),
        EditorHtmlHelper.renderControl({
            label: 'Radius',
            type: 'bound',
            name: 'radius',
            items: ['左上', '右上', '右下', '左下']
        }),
        EditorHtmlHelper.renderControl({
            label: 'Shadow',
            type: 'shadow',
            name: 'shadow'
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
        const children = this.items.map(i => i.render()).join('');
        const html = `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
    </div>
    <div class="control-popup" data-popup="border">
        ${children}
    </div>`
        return EditorHtmlHelper.input(this, this.label, html);
    }

    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        this.manager = manager;
        const items = box.find('.control-popup').children();
        this.items.forEach((item, i) => {
            item.ready(items.eq(i), this);
        });
    }

    notify(control: IEditorInput): void {
        this.manager.notify(this);
    }
}


class EditorShadowControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    value: any;
    input?: IVisualInput;
    label?: string;

    private items: IEditorElement[] = [
        new EditorBoundElement(undefined, ['X', 'Y', 'BLUR', 'SPREAD']),
        EditorHtmlHelper.renderControl({
            label: 'Color',
            type: 'color',
            name: 'color'
        }),
        EditorHtmlHelper.renderControl({
            label: 'Inset',
            type: 'switch',
            name: 'inset',
            items: ['内', '外']
        }),
    ];
    private element: JQuery<HTMLElement>;
    private manager: IEditorInputGroup;

    get isUpdated(): boolean {
        for (const item of this.items) {
            if (Object.prototype.hasOwnProperty.call(item, 'isUpdated') && (item as IEditorInput).isUpdated) {
                return true;
            }
        }
        return false;
    }

    reset(): void {
        this.value = undefined;
        this.items.forEach(item => {
            if ((item as IEditorInput).reset) {
                (item as IEditorInput).reset();
            }
        });
    }

    render(): string {
        const children = this.items.map(i => i.render()).join('');
        const html =  `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
    </div>
    <div class="control-popup" data-popup="shadow">
        ${children}
    </div>`;
        return EditorHtmlHelper.input(this, this.label, html);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        this.manager = manager;
        const items = box.find('.control-popup').children();
        const that = this;
        this.items.forEach((item, i) => {
            item.ready(items.eq(i), that);
        });
    }

    notify(control: IEditorInput): void {
        this.value = {};
        for (const item of this.items) {
            if ((item as IEditorInput).reset) {
                this.value[(item as IEditorInput).name] = (item as IEditorInput).value;
            }
        }
        this.manager.notify(this);
    }
}

