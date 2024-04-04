class EditorHtmlHelper {
    private static _guid = 0;
    private static _cacheData: any = {};
    public static guid(): number {
        return this._guid ++;
    }

    public static shimmed(): string {
        return `_zreinput="${this.guid()}"`;
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

    public static set(data: any): string {
        const key = 'cache'+ this.guid();
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

    public static horizontalAlign(id: string = 'horizontal-align', name: string = 'horizontal-align', value?: any, isTextAlign = false): string {
        const items = [
            {name: '<i class="fa fa-align-left" title="左对齐"></i>', value: 'left'},
            {name: '<i class="fa fa-align-center" title="水平居中"></i>', value: 'center'},
            {name: '<i class="fa fa-align-right" title="右对齐"></i>', value: 'right'},
        ];
        if (!isTextAlign) {
            items.push({name: '<i class="fa fa-align-justify" title="水平等间距"></i>', value: 'stretch'});
        }
        return this.radioInput(id, name, items, value);
    }

    public static verticalAlign(id: string = 'vertical-align', name: string = 'vertical-align', value?: any): string {
        return this.radioInput(id, name, [
            {name: '<i class="fa fa-align-left fa-vertical" title="上对齐"></i>', value: 'top'},
            {name: '<i class="fa fa-align-center fa-vertical" title="垂直居中"></i>', value: 'center'},
            {name: '<i class="fa fa-align-right fa-vertical" title="下对齐"></i>', value: 'bottom'},
            {name: '<i class="fa fa-align-justify fa-vertical" title="垂直等间距"></i>', value: 'stretch'},
        ], value);
    }


    public static positionSide(type: string, val?: any) {
        const name = `settings[style][position][value]`;
        return type !== 'static' ? this.boundInput(this.nameToId(name), name, val) : '';
    }


    /**
     * 生成原生select
     * @param id 
     * @param name 
     * @param items 
     * @param val 
     * @returns 
     */
    private static selectControl(id: string, name: string, items: IItem[], val?: any, input?: IVisualInput) {
        return `<select class="form-control" name="${name}" id="${id}"${this.renderAttribute(input)}>
        ${this.selectOption(items, val)}
        </select>`;
    }

    private static sideInput(name: string, label: string, vals: string[] = []) {
        const id = this.nameToId(name) + '_' + this.guid();
        return this.input(id, label, this.boundInput(id, name, vals), 'control-line-group');
    }

    public static switch(id: string, name: string, val: string|number|boolean, onLabel = '开启', offLabel = '关闭') {
        val = EditorHelper.parseNumber(val);
        return this.join('<div id="', id,'" class="switch-input', val > 0 ? ' checked' : '' ,'" data-on="',onLabel,'" data-off="', offLabel ,'"><span class="switch-control"></span><span class="switch-label">', val > 0 ? onLabel : offLabel ,'</span><input type="hidden" name="', name,'" value="', val, '"/></div>');
    }

    private static radioInput(id: string, name: string, items: IItem[], selected?: string| number) {
        return this.join('<div class="control-row">', ...items.map((item, j) => {
            const index = [id, j].join('_');
            const chk = item.value == selected ? ' checked' : '';
            return `<span class="radio-control-item${chk}" data-value="${item.value}">${item.name}</span>`;
        }), `<input type="hidden" name="${name}" value="${this.value(selected)}">`, '</div>');
    }

    private static checkbox(id: string, name: string, items: IItem[], val: string[] = []) {
        return this.join('<div class="control-row">', ...items.map((item, j) => {
            const index = [id, j].join('_');
            const chk = val && val.indexOf(item.value) >= 0 ? ' checked' : '';
            return `<span class="check-label radio-control-item"><input type="checkbox" id="${index}" name="${name}" value="${item.value}"${chk}><label for="${index}">${item.name}</label></span>`;
        }), '</div>');
    }

    private static text(id: string, name: string, val: string = '', size?: number) {
        const option = size ? ` size="${size}"` : '';
        val = this.value(val);
        return `<input type="text" class="form-control" id="${id}" name="${name}" value="${val}"${option}>`;
    }

    private static input(id: string, label: string, content: string, cls: string = 'control-inline-group'): string {
        return `<div ${this.shimmed()} class="${cls}"><i class="control-updated-tag hidden"></i><label for="${id}">${label}</label>${content}</div>`;
    }

    public static buttonGroup(...items) {
        return this.join('<div class="btn-group control-offset">', ...items, '</div>');
    }

    public static button(text: string, cls: string) {
        return `<button type="button" class="btn ${cls}">${text}</button>`;
    }

    private static animationInput() {
        // 动效名称
        const animationLabelOptions = [
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
        ];

        // 延时时间
        const animationLabelDelayOptions = [
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
        ];

        // 时长
        const animationLabelDurationOptions = [
            { value: '0.25s', name: '250ms' },
            { value: '0.5s', name: '500ms' },
            { value: '0.75s', name: '750ms' },
            { value: '1s', name: '1s' },
            { value: '2s', name: '2s' },
            { value: '3s', name: '3s' },
            { value: '4s', name: '4s' },
            { value: '5s', name: '5s' },
        ];

        // 重复次数
        const animationIterationCountOptions = [
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
        ];

        const animationFuncOptions = [
            { value: 'linear', name: '线性' },
            { value: 'ease', name: 'ease' },
            { value: 'ease-in', name: 'ease-in' },
            { value: 'ease-out', name: 'ease-out' },
            { value: 'ease-in-out', name: 'ease-in-out' },
        ];
        return `<div class="animation-container">
        <div class="effect-show">效果展示</div>
        <div class="control-inline-group">
            <label for="animation-type">动效</label>
            <select class="form-control" id="animation-type" name="animation[type]">
            ${this.selectOption(animationLabelOptions)}
            </select>
        </div>
        <div class="control-inline-group">
            <label for="animation-delay">延时</label>
            <select class="form-control" id="animation-delay" name="animation[delay]">
            ${this.selectOption(animationLabelDelayOptions)}
            </select>
        </div>
        <div class="control-inline-group">
            <label for="animation-duratio">时长</label>
            <select class="form-control" id="animation-duration" name="animation[duration]">
            ${this.selectOption( animationLabelDurationOptions)}
            </select>
        </div>
        <div class="control-inline-group">
            <label for="animation-count">重复</label>
            <select class="form-control" id="animation-count" name="animation[count]">
            ${this.selectOption(animationIterationCountOptions)}
            </select>
        </div>
    </div>`;
    }

    private static backgroundPopup(id: string, name: string, val?: any) {
        return `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
    </div>
    <div class="control-popup" data-popup="background">
        <div class="control-inline-group">
            <label for="">背景色</label>
            ${this.colorPopup(id, name + '[color]')}
        </div>
        <div class="control-line-group">
            <label for="">背景图片</label>
            ${this.imageInput(id, name + '[image]')}
        </div>
    </div>
    `;
    }

    private static imageInput(id: string, name: string, value?: any) {
        if (!value) {
            return `<label class="drag-control-container" for="${id}">
            拖放文件
            <p>(或点击)</p>
            <input type="file" id="${id}" name="${name}" accept="image/*">
            <div class="loading">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </label>`;
        }
        return `<div class="image-control-item">
            <div class="control-body">
                <img src="${value}">
            </div>
            <div class="control-action"></div>
                <i class="fa fa-edit"></i>
                <i class="fa fa-trash"></i>
            </div>
        </div>`;
    }

    private static shadowPopup(id: string, name: string, val?: any) {
        const html = ['X', 'Y', 'BLUR', 'SPREAD'].map((label, i) => {
            return this.sizeInput(label, id + '-' + i, `${name}[${i}]`)
        });
        return `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
    </div>
    <div class="control-popup" data-popup="shadow">
        <div class="control-row">
            ${html.join('')}
        </div>
       
        <div class="control-inline-group">
            <label for="">Color</label>
            ${this.colorPopup(id, name + '[color]')}
        </div>
        <div class="control-inline-group">
            <label for="">Inset</label>
            ${this.switch(id,name + '[inset]', '', '内', '外')}
        </div>
    </div>`;
    }

    private static selectOption(items: IItem[], selected?: any) {
        return items.map(item => {
            const sel = selected == item.value ? ' selected' : '';
            return `<option value="${item.value}"${sel}>${item.name}</option>`;
        }).join('');
    }

    private static borderPopup(id: string, name: string, val?: any) {
        const styleItems = [
            {name: '无', value: ''},
            {name: '横线', value: 'solid'},
            {name: '点线', value: 'dotted'},
            {name: '虚线', value: 'double'},
        ];
        return `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
    </div>
    <div class="control-popup" data-popup="border">
        <div class="control-line-group">
            <label for="">Width</label>
            ${this.boundInput(id,name+'[width]')}
        </div>
        <div class="control-inline-group">
            <label for="">Style</label>
            <select name="${name+'[style]'}">
                ${this.selectOption(styleItems)}
            </select>
        </div>
        <div class="control-inline-group">
            <label for="">Color</label>
            ${this.colorPopup(id,name+'[color]')}
        </div>
        <div class="control-line-group">
            <label for="">Radius</label>
            ${this.radiusInput(id,name+'[radius]')}
        </div>
        <div class="control-inline-group">
            <label for="">Shadow</label>
            ${this.shadowPopup(id,name+'[shadow]')}
        </div>
    </div>`;
    }

    private static iconPopup(id: string, name: string, val?: any) {
        const iconItems = [
            {name: 'Home', value: 'fa-home'},
            {name: 'Edit', value: 'fa-edit'},
            {name: 'close', value: 'fa-times'},
            {name: 'trash', value: 'fa-trash'},
        ];
        const html = iconItems.map(item => `<div class="icon-option-item" data-value="${item.value}">
        <i class="fa ${item.value}"></i>
        <span>${item.name}</span>
        </div>`).join('')
        return `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
        <input type="hidden" name="${name}" value="${val}">
    </div>
    <div class="control-popup" data-popup="icon">
        <div class="search-header-bar">
            <input>
            <i class="fa fa-search"></i>
        </div>
        <div class="search-body">
            ${html}
        </div>
    </div>`;
    }

    private static colorPopup(id: string, name: string, val?: any) {
        // return `<input type="color" class="form-control-color" id="${id}" name="${name}">`;
        return `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
        <input type="hidden" name="${name}" value="${val}">
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
    }
    public static colorPopupColor(popup: JQuery<HTMLDivElement>): IHslColor;
    public static colorPopupColor(popup: JQuery<HTMLDivElement>, color: string): void;
    public static colorPopupColor(popup: JQuery<HTMLDivElement>, color?: string): void|IHslColor {
        const hCtl = popup.find('.hue-bar');
        const sCtl = popup.find('.saturation-bar');
        const lCtl = popup.find('.lightness-bar');
        const tCtl = popup.find('.alpha-bar');
        if (typeof color === 'undefined') {
            return {h: toInt(hCtl.val()), s: toInt(sCtl.val()), l: toInt(lCtl.val()), a: toInt(tCtl.val()) / 100};
        }
        const format = EditorHelper.colorToRgba(color);
        const hsl = EditorHelper.rgbaToHsl(format);
        hCtl.val(hsl.h);
        sCtl.val(hsl.s);
        lCtl.val(hsl.l);
        tCtl.val(hsl.a * 100);
    }

    private static boundInput(id: string, name: string, items?: any[]) {
        const html = ['上', '右', '下', '左'].map((label, i) => {
            return this.sizeInput(label, `${id}_${i}`, `${name}[${i}]`, items && items.length > i ? items[i] : '');
        }).join('');
        return `<div class="control-row">${html}</div>`
    }


    private static maskInput() {
        return `<div class="mark-container">
        <div class="clip-path-box">
            <div class="shape" style="clip-path: inset(10%);"></div>
            <div class="shape" style="clip-path: circle(45% at 50% 50%);"></div>
            <div class="shape" style="clip-path: ellipse(30% 45% at 50% 50%);"></div>
            <div class="shape"
                style="clip-path: polygon(50% 0%, 0% 100%, 100% 100%);"
            ></div>
            <div
                class="shape"
                style="clip-path: polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%);"
            ></div>
            <div
                class="shape"
                style="clip-path: polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%);"
            ></div>
            <div
                class="shape"
                style="clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);"
            ></div>
            <div
                class="shape"
                style="clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%);"
            ></div>
            <div
                class="shape"
                style="clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);"
            ></div>
        </div>
        <button class="btn btn-primary">一键取消</button>
    </div>
    `;
    }


    private static radiusInput(id: string, name: string, items?: any[]) {
        const html = ['左上', '右上', '右下', '左下'].map((label, i) => {
            return this.sizeInput(label, `${id}_${i}`, `${name}[]`, items && items.length > i ? items[i] : '');
        }).join('');
        return `<div class="control-row">${html}</div>`
    }

    private static selectInput(id: string, name: string, val: any, items: string[]|IItem[], editable = false, searchable = false, arrow = 'fa-angle-down') {
        const body = editable ? `<input type="text" class="input-body" id="${id}" name="${name}" value="${val}">` : `<span class="input-body">${val}</span>`;

        return this.join(`<div class="select-control-container">`, `<div class="select-input-container">
        ${body}
        <div class="input-clear">
            <i class="fa fa-close"></i>
        </div>
        <div class="input-arrow">
            <i class="fa ${arrow}"></i>
        </div>
    </div>`, this.selectOptionBar(items, val, searchable), `</div>`);

    }

    private static sizeInput(label: string|undefined, id?: string, name?: string, val?: any, unit: string = 'px') {
        if (typeof val === 'object' && val.value) {
            unit = val.unit;
            val = val.value;
        }
        val = toFloat(val);
        const input = `<input type="number" id="${id}" name="${name}[value]" value="${val}">`;
        const core = label ? `<div class="control-body">
        <label for="${id}">${label}</label>
        ${input}
        <input class="extra-control" name="${name}[unit]" value="${unit}" spellcheck="false">
    </div>` : `<div class="control-body">
    <input class="extra-control" name="${name}[unit]" value="${unit}" spellcheck="false">
    ${input}
</div>`;
        return `<div class="select-with-control">` + core + this.selectOptionBar(['px', 'em', 'rem', 'vh', 'vw', '%', 'auto', 'none']) + '</div>';
    }

    private static selectOptionBar(items: string[]|IItem[], selected?: any, searchable = false) {
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

    private static treeInput(input: IVisualInput[], items?: any[]) {
        let html = '';
        if (items) {
            for (const item of items) {
                html += this.renderTreeItem(input, item);
            }
        }
        return `${html}<div class="tree-add-btn">
        <i class="fa fa-plus"></i>
    </div>`;
    }

    private static renderMultipleItem(input: IVisualInput[], value?: any) {
        let icon = '';
        if (value && value.icon) {
            icon = '<i class="item-icon fa '+ value.icon +'"></i>';
        }
        const title = value ? value.title : '';
        return `<div class="list-item">
        ${icon}
            <span class="item-title">${title}</span>
            <div class="item-action-icon">
                <i class="fa fa-ellipsis-h"></i>
                <div class="item-action-bar">
                    <i class="fa fa-trash"></i>
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-arrow-up"></i>
                    <i class="fa fa-arrow-down"></i>
                </div>
            </div>
        </div>`
        // return `<div class="multiple-control-item">
        //         <div class="multiple-control-header">
        //             <span>${value?.title}</span>
        //             <div class="control-action">
        //                 <i class="fa fa-edit"></i>
        //                 <i class="fa fa-trash"></i>
        //             </div>
        //         </div>
        //         <div class="multiple-control-body">
        //         ${this.renderForm(input, value)}
        //         </div>
        //     </div>`;
         
    }
    
    private static renderTreeItem(input: IVisualInput[], item?: any) {
        let html = '';
        let icon = '';
        if (item && item.children) {
            html = '<div class="item-children">' + this.treeInput(input, item.children) + '</div>';
            icon = '<div class="item-open-icon"><i class="fa fa-chevron-right"></i></div>';
        }
        if (item && item.icon) {
            icon = '<i class="item-icon fa '+ item.icon +'"></i>';
        }
        const title = item ? item.title : '';
        return `<div class="tree-item">
        <div class="item-body">
            ${icon}
            <span class="item-title">${title}</span>
            <div class="item-action-icon">
                <i class="fa fa-ellipsis-h"></i>
                <div class="item-action-bar">
                    <i class="fa fa-trash"></i>
                    <i class="fa fa-plus"></i>
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-arrow-up"></i>
                    <i class="fa fa-arrow-down"></i>
                </div>
            </div>
        </div>
        ${html}
    </div>`
    }
    private static nameToId(name: string) {
        return name.replace(/\[/g, '_').replace(/\]/g, '');
    }

    private static renderAttribute(item: IVisualInput): string {
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

    private static renderClass(item: IVisualInput, hasKey = true): string {
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
        return form.map(this.renderInput.bind(this)).join('');
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
        target.find('input,textarea,select,.multiple-container,.tree-container').each(function(this: HTMLInputElement) {
            const $this = $(this);
            if ($this.hasClass('multiple-container') || $this.hasClass('.tree-container')) {
                const input = that.get<IVisualInput>($this.data('cache'));
                if (isObject) {
                    data[input.name] = input.items;
                    return;
                }
                data.push(encodeURIComponent(input.name) + '=' +
                    encodeURIComponent( JSON.stringify(input.items) ));
                return;
            }
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
        });
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

    private static renderInput(input: IVisualInput): string {
        const id = input.name + '_' + this.guid();
        const func = this[input.type + 'Execute'];
        if (typeof func === 'function') {
            return func.call(this, input, id);
        }
        return this.textExecute(input, id);
        // throw new Error(`input type error:[${input.type}]`);
    }

    private static groupExecute(input: IVisualInput) {
        if (!input.label) {
            return `<div${this.renderClass(input)}>${this.render(input.items)}</div>`
        }
        return `<div class="expand-box">
        <div class="expand-header">${input.label}<span class="fa fa-chevron-right"></span></div>
        <div class="expand-body">
        ${this.render(input.items)}
        </div>
    </div>`
    }

    private static alignExecute(input: IVisualInput, id: string) {
        return this.horizontalAlign() + this.verticalAlign();
    }

    private static borderExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.borderPopup(id, input.name, input.value));
    }


    private static textAlignExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.horizontalAlign(id, input.name, input.value, true), 'control-line-group');
    }
    
    private static colorExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.colorPopup(id, input.name, input.value));
    }

    private static backgroundExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.backgroundPopup(id, input.name, input.value));
    }

    private static iconExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.iconPopup(id, input.name, input.value));
    }

    private static numberExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, `<input type="number" id="${id}" name="${input.name}" value="${input.value}">`);
    }

    private static textareaExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, `<textarea class="form-control" id="${id}" name="${input.name}">${input.value}</textarea>`, 'control-line-group');
    }

    private static sizeExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.sizeInput(undefined, id, input.name, input.value));
    }

    private static rangeExecute(input: IVisualInput, id: string) {
        const items = [];
        for (let i = input.min; i <= input.max; i+= input.step) {
            items.push({name: i, value: i});
        }
        return this.input(id, input.label, this.selectControl(id, input.name, items, input.value, input));
    }

    private static selectExecute(input: IVisualInput, id: string) {
        if (!input.search && input.items) {
            return this.input(id, input.label, this.selectControl(id, input.name, input.items, input.value, input));
        }
        return this.input(id, input.label, this.selectInput(id, input.name, input.items, input.value));
    }

    private static positionExecute(input: IVisualInput, id: string) {
        const data = input.value ?? []; 
        const typeItems = [
            {name: '无', value: 'static'},
            {name: '相对定位', value: 'relative'},
            {name: '绝对定位', value: 'absolute'},
            {name: '固定定位', value: 'fixed'},
        ];
        const type = !data.type ? typeItems[0].value : data.type;
        const name = `${input.name}[value]`;
        const bound = type !== typeItems[0].value ? this.boundInput(this.nameToId(name), name, data.value) : '';
        const select = this.input(id, input.label, this.selectControl(id, input.name + '[type]', typeItems, type));
        return  `<div class="position-container">
        ${select}
        <div>${bound}</div>
    </div>`
    }

    private static boundExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.boundInput(id, input.name, input.value)
        , 'control-line-group');
    }

    private static switchExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.switch(id, input.name, input.value));
    }

    private static textExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, input.items && input.items.length > 0 ?  this.selectInput(id, input.name, input.value, input.items, true, false, 'fa-bolt') : this.text(id, input.name, input.value)
        , 'control-line-group');
    }

    private static imageExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, this.imageInput(id, input.name, input.value)
        , 'control-line-group');
    }

    private static imagesExecute(input: IVisualInput, id: string) {
        const items = [];
        const name = input.name + '[]';
        if (input.items) {
            for (const item of input.items) {
                items.push(this.imageInput(id, name, item));
            }
        }
        items.push(this.imageInput(id, name));
        return this.input(id, input.label, 
            items.join('')
        , 'control-line-group');
    }

    private static multipleExecute(input: IVisualInput, id: string) {
        const items = [];
        const name = input.name + '[]';
        if (input.items) {
            for (const item of input.items) {
                items.push(this.renderMultipleItem(input.input, item));
            }
        }
        items.push(`<div class="multiple-add-btn">
            <i class="fa fa-plus"></i>
        </div>`);
        return this.input(id, input.label, 
            this.flipPanel(`<div class="multiple-container" data-cache="${this.set(input)}">${items.join('')}</div>`)
        , 'control-line-group');
    }

    private static treeExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, 
            this.flipPanel(`<div class="tree-container" data-cache="${this.set(input)}">${this.treeInput(input.input, input.items)}</div>`)
        , 'control-line-group');
    }

    private static flipPanel(body: string) {
        return `<div class="flip-container"><div class="flip-front-body">${body}</div><div class="flip-back-body">
            <div class="flip-action-bar">
                <i class="fa fa-arrow-left flip-back-btn"></i>
                <i class="fa fa-save flip-save-btn"></i>
            </div>
            <div class="flip-body"></div>
        </div></div>`;
    }

    private static htmlExecute(input: IVisualInput, id: string) {
        return this.input(id, input.label, `<textarea class="form-control" id="${id}" name="${input.name}">${input.value}</textarea>`, 'control-line-group');
    }

    private static getData(data: any[], index: number[]): any {
        let i = index.length - 1;
        while (i >= 0) {
            const j = index[i];
            if (j >= data.length) {
                return undefined;
            }
            const res = data[j];
            if (i === 0) {
                return res;
            }
            data = res.children;
            i --;
        }
        return undefined;
    }

    private static setData(data: any[], index: number[], val: any) {
        let i = index.length - 1;
        while (i >= 0) {
            const j = index[i];
            if (i === 0) {
                if (j >= data.length) {
                    data.push({...val});
                } else {
                    data[j] = {...val};
                }
            }
            if (!data[j].children) {
                data[j].children = [];
            }
            data = data[j].children;
            i --;
        }
    }

    private static moveItem(data: any[], source: number[], dist: number[]) {
        let item: any;
        let temp = data;
        let i = source.length - 1;
        while (i >= 0) {
            const j = source[i];
            if (i === 0) {
                if (j < temp.length) {
                    item = temp[j];
                    temp.splice(j, 1);
                }
                break;
            }
            if (!temp[j].children) {
                temp[j].children = [];
            }
            temp = temp[j].children;
            i --;
        }
        if (!item) {
            return;
        }
        temp = data;
        i = dist.length - 1;
        while (i >= 0) {
            const j = dist[i];
            if (i === 0) {
                if (j < temp.length) {
                    temp.splice(j, 0, item);
                } else {
                    temp.push(item);
                }
                break;
            }
            if (!temp[j].children) {
                temp[j].children = [];
            }
            temp = temp[j].children;
            i --;
        }
    }

    private static removeItem(data: any[], index: number[]) {
        let i = index.length - 1;
        while (i >= 0) {
            const j = index[i];
            if (i === 0) {
                if (j < data.length) {
                    data.splice(j, 1);
                }
                return;
            }
            if (!data[j].children) {
                data[j].children = [];
            }
            data = data[j].children;
            i --;
        }
    }

    public static bindInputEvent(box: JQuery<HTMLDivElement>) {
        const that = this;
        box.on('change', '.position-container select', function() {
            const $this = $(this);
            $this.next().html(EditorHtmlHelper.positionSide($this.val() as string));
        })
        // switch
        .on('click', '.switch-input', function() {
            const $this = $(this);
            const checked = !$this.hasClass('checked');
            $this.toggleClass('checked', checked);
            $this.find('.switch-label').text(checked ? $this.data('on') : $this.data('off'));
            const val = checked ? 1 : 0;
            $this.find('input').val(val).trigger('change');
            const box = $this.closest('.control-inline-group');
            box.trigger(EditorEventInputChange, val)
            if ($this.data('tab')) {
                box.trigger(EditorEventTabToggle, [$this.data('tab'), $this.val()]);
            }
        })
        .on(EditorEventInputReset, '.switch-input', function() {
            $(this).trigger('click');
        })
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
                EditorHtmlHelper.colorPopupColor($this, '#0000');
                $this.find('.tab-bar-target input').val('');
                return;
            }
            $this.children('.control-inline-group,.control-line-group').children('label').next().trigger(EditorEventInputReset);
        })
        .on('click', '.control-popup .popup-action .btn', function() {
            const $this = $(this);
            if ($this.hasClass('btn-danger')) {
                $this.closest('.control-popup').trigger(EditorEventInputReset).prev('.control-popup-target').trigger(EditorEventInputReset).
                prev().prev('.control-updated-tag').addClass('hidden');
                return;
            }
        })
        // icon
        .on('click', '.icon-option-item', function() {
            const $this = $(this);
            $this.addClass('selected').siblings().removeClass('selected');
            $this.closest('.control-popup').trigger(EditorEventInputChange, $this.data('value'));
        })
        .on('change', '.control-popup .search-header-bar input', function() {
            const $this = $(this);
            const val = $this.val();
            $this.closest('.search-header-bar').next('.search-body').find('.icon-option-item').each(function() {
                const item = $(this);
                const text = item.find('span').text();
                item.toggleClass('hidden', val && text.indexOf(val) < 0);
            });
        })
        // select
        .on('change', '.select-with-control .control-body input', function() {
            const $this = $(this);
            let target = $this.parent();
            if (!target.hasClass('.control-inline-group')) {
                target = target.closest('.control-line-group');
            }
            target.trigger(EditorEventInputChange, $this.val());
        })
        .on(EditorEventInputReset, '.select-with-control', function() {
            $(this).find('.control-body input').val('');
        })
        .on('click', '.select-with-control .control-body .extra-control', function() {
            $(this).closest('.select-with-control').toggleClass('select-focus');
        }).on('select:change', '.select-with-control .select-option-bar', function(_, ele: HTMLDivElement) {
            const $this = $(ele);
            $this.closest('.select-with-control').removeClass('select-focus').find('.control-body .extra-control').val($this.text());
        }).on('click', '.select-option-bar .option-item', function() {
            const $this = $(this);
            $this.addClass('selected').siblings().removeClass('selected');
            $this.closest('.select-option-bar').trigger('select:change', this);
        })
        // color
        .on('change', '.hue-bar,.saturation-bar,.lightness-bar,.alpha-bar', function() {
            const colorPopup = $(this).closest('.control-popup');
            const hsl = EditorHtmlHelper.colorPopupColor(colorPopup);
            const rgba = EditorHelper.hslToRgba(hsl);
            const format = `rgba(${rgba.r}, ${rgba.g}, ${rgba.b}, ${rgba.a})`;
            colorPopup.find('.saturation-bar').css('background-image', `linear-gradient(to right, gray, ${format})`);
            colorPopup.find('.lightness-bar').css('background-image', `linear-gradient(to right, black, ${format})`);
            colorPopup.find('.alpha-bar').css('background-image', `linear-gradient(to right, transparent, ${format})`);
            colorPopup.find('.tab-bar-target').each(function(i) {
                const that = $(this);
                if (i === 0) {
                    that.find('input').val(EditorHelper.rgbaToHex(rgba));
                } else {
                    const items = i === 1 ? [rgba.r, rgba.g, rgba.b, rgba.a] : [hsl.h, hsl.s, hsl.l, hsl.a];
                    that.find('input').each(function(this: HTMLInputElement, j) {
                        this.value = items[j].toString();
                    });
                }
            });
            colorPopup.trigger(EditorEventInputChange, EditorHelper.rgbaToHex(rgba))
        })
        // select
        .on('change', '.select-control-container .input-body', function() {
            const $this = $(this);
            const val = this instanceof HTMLInputElement ? this.value : $this.text();
            $this.next('.input-clear').toggle(!!val);
            $this.closest('.select-control-container').find('.select-option-bar .option-item').each(function() {
                const that = $(this);
                that.toggleClass('selected', val === that.data('value'));
            });
            $this.closest('.control-line-group').trigger(EditorEventInputChange, val);
        }).on('click', '.select-control-container .input-clear', function() {
            const target = $(this).prev('.input-body');
            if (target[0] instanceof HTMLInputElement) {
                target.val('');
            } else {
                target.text('');
            }
            target.trigger('change');
        }).on('click', '.select-control-container .input-arrow', function() {
            $(this).closest('.select-control-container').toggleClass('select-focus');
        }).on('select:change', '.select-control-container .select-option-bar', function(_, ele: HTMLDivElement) {
            const $this = $(ele);
            const target = $this.closest('.select-control-container').removeClass('select-focus').find('.input-body');
            if (target[0] instanceof HTMLInputElement) {
                target.val($this.data('value'));
            } else {
                target.text($this.data('value'));
            }
            target.trigger('change');
        })
        .on(EditorEventInputReset, '.select-control-container', function() {
            $(this).find('.input-clear').trigger('click');
        })
        // tabbar 
        .on('click', '.tab-bar .item', function() {
            const $this = $(this);
            $this.addClass('active').siblings().removeClass('active');
            $this.closest('.tab-bar').siblings('.tab-bar-target').removeClass('active').eq($this.index()).addClass('active');
        })
        // 图片上传
        .on('change', '.drag-control-container input[type=file]', function(this: HTMLInputElement) {
            const target = $(this).closest('.drag-control-container');
            target.addClass('is-uploading');
            
        })
        // radio
        .on(EditorEventInputReset, '.control-row', function(e) {
            if (!$(e.target).hasClass('control-row')) {
                return;
            }
            $(this).children().trigger(EditorEventInputReset);
        })
        .on('click', '.radio-control-item', function() {
            const $this = $(this);
            const isChecked = !$this.hasClass('checked');
            $this.toggleClass('checked', isChecked);
            if (!$this.hasClass('check-label') && isChecked) {
                $this.siblings().removeClass('checked');
            }
            const val = isChecked ? $this.data('value') : undefined;
            const target = $this.closest('.control-row');
            target.find('input').val(val);
            const group = target.closest('.control-line-group');
            group.trigger(EditorEventInputChange, val);
            if (target.data('tab')) {
                group.trigger(EditorEventTabToggle, [target.data('tab'), val]);
            }
        })
        .on(EditorEventInputReset, '.radio-control-item', function() {
            const $this = $(this);
            if ($this.hasClass('checked')) {
                $this.trigger('click');
            }
        })
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
        }).on(EditorEventInputChange, '.control-inline-group,.control-line-group', function(e, val: any) {
            e.stopPropagation();
            const $this = $(this);
            const popup = $this.closest('.control-popup');
            if (popup.length > 0) {
                popup.trigger(EditorEventInputChange, val);
                return;
            }
            $this.find('.control-updated-tag:first').toggleClass('hidden', !val);
        })
        .on('click', '.control-updated-tag', function() {
            const $this = $(this);
            $this.next().next().trigger(EditorEventInputReset).next('.control-popup').trigger(EditorEventInputReset);
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
        .on(EditorEventFlipToggle, '.flip-container', function(_, data: {body: string, callback: (data: any) => void}) {
            const $this = $(this);
            $this.addClass('flip-toggle');
            const form = $this.find('.flip-body');
            form.html(data.body);
            $this.one(EditorEventFlipFinish, (_, res: boolean) => {
                $this.removeClass('flip-toggle');
                if (res) {
                    data.callback(that.formData(form, 'item_'));
                }
                form.empty();
            });
        })
        .on(EditorEventListEdit, '.tree-container,.multiple-container', function(_, target: HTMLElement|JQuery<HTMLElement>) {
            if (target instanceof HTMLElement) {
                target = $(target);
            }
            const isTree = target.hasClass('.tree-item');
            const map = isTree ? [target.index(), ...$(this).parents('.tree-item').map((_, j) => $(j).index()).toArray()] : [target.index()];
            const formData = that.get<IVisualInput>($(this).data('cache'));
            target.closest('.flip-container').trigger(EditorEventFlipToggle, {
                body: that.renderForm(formData.input, that.getData(formData.items, map)),
                callback: data => {
                    target.replaceWith(isTree ? that.renderTreeItem(formData.input, data) : that.renderMultipleItem(formData.input, data));
                    that.setData(formData.items, map, data);
                    target.closest('.control-line-group').trigger(EditorEventInputChange, formData.items);
                }
            });
        })
        // tree 
        .on('click', '.tree-container .tree-add-btn,.multiple-container .multiple-add-btn', function() {
            const $this = $(this);
            const box = $this.closest('.tree-container,.multiple-container');
            const formData = that.get<IVisualInput>(box.data('cache')).input;
            const target = $(box.hasClass('tree-container') ? that.renderTreeItem(formData) : that.renderMultipleItem(formData));
            $this.before(target);
            box.trigger(EditorEventListEdit, target);
        }).on('click', '.tree-container .item-body', function() {
            const $this = $(this);
            
        }).on('click', '.tree-container .item-action-bar .fa-arrow-up,.multiple-container .item-action-bar .fa-arrow-up', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.tree-container,.multiple-container');
            const target = $this.closest('.tree-item,.list-item');
            const prev = target.prev();
            if (prev.length === 0) {
                return;
            }
            const index = target.index();
            const map = box.hasClass('tree-container') ? $(this).parents('.tree-item').map((_, j) => $(j).index()).toArray() : [];
            const data = that.get<IVisualInput>(box.data('cache')).items;
            that.moveItem(data, [index, ...map], [index - 1, ...map]);
            prev.before(target);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.tree-container .item-action-bar .fa-arrow-down,.multiple-container .item-action-bar .fa-arrow-down', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.tree-container,.multiple-container');
            const target = $this.closest('.tree-item,.list-item');
            const next = target.next();
            if (next.length === 0) {
                return;
            }
            const index = target.index();
            const map = box.hasClass('tree-container') ? $(this).parents('.tree-item').map((_, j) => $(j).index()).toArray() : [];
            const data = that.get<IVisualInput>(box.data('cache')).items;
            that.moveItem(data, [index, ...map], [index + 1, ...map]);
            next.after(target);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.tree-container .item-action-bar .fa-edit,.multiple-container .item-action-bar .fa-edit', function(e) {
            e.stopPropagation();
            const target = $(this).closest('.tree-item,.list-item');
            const box = target.closest('.tree-container,.multiple-container');
            box.trigger(EditorEventListEdit, target);
        })
        .on('click', '.tree-container .item-action-bar .fa-plus', function(e) {
            e.stopPropagation();
            const item = $(this).closest('.item-body');
            let next = item.next('.item-children');
            if (next.length === 0) {
                next = $(`<div class="item-children"></div>`);
                item.after(next);
                item.prepend('<div class="item-open-icon"><i class="fa fa-chevron-right"></i></div>');
            }
            const formData = that.get<IVisualInput>(item.closest('.tree-container').data('cache')).input;
            const target = $(that.renderTreeItem(formData));
            next.append(target);
            box.trigger(EditorEventListEdit, target);
        }).on('click', '.tree-container .item-action-bar .fa-trash,.multiple-container .item-action-bar .fa-trash', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.tree-container,.multiple-container');
            const target = $this.closest('.tree-item,.list-item');
            const map = box.hasClass('tree-container') ? [target.index(), ...$(this).parents('.tree-item').map((_, j) => $(j).index()).toArray()] : [target.index()];
            target.remove();
            const data = that.get<IVisualInput>(box.data('cache')).items;
            that.removeItem(data, map);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.tree-container .item-open-icon', function(e) {
            e.stopPropagation();
            $(this).closest('.tree-item').toggleClass('open');
        });
    }
}
