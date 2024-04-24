class EditorTreeControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    value: any;
    form: IVisualInput[];
    label?: string;

    private element: JQuery<HTMLElement>;
    private items: IEditorInput[] = [];

    get isUpdated(): boolean {
        return this.value && this.value.length > 0;
    }

    constructor(
        input: IVisualInput
    ) {
        this.value = input.items;
        this.form = input.input;
    }

    reset(): void {
        this.value = [];
        this.element.find('.tree-container').children('.tree-item').remove();
    }
    render(): string {
        this.items = EditorHtmlHelper.renderControlItems(this.form);
        return EditorHtmlHelper.input(this, this.label, 
            EditorTreeControl.flipPanel(`<div class="tree-container">${this.treeInput(this.form, this.value)}</div>`, this.items.map(i => i.render()).join(''))
        , 'control-line-group');
    }

    notify(control: IEditorElement): void {}
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        const that = this;
        const items = box.find('.flip-back-body .flip-body').children();
        this.items.forEach((item, i) => {
            item.ready(items.eq(i), this);
        });
        box// tree 
        .on('click', '.tree-add-btn', function() {
            const $this = $(this);
            const box = $this.closest('.tree-container');
            const target = $(that.renderTreeItem(that.form));
            $this.before(target);
            box.trigger(EditorEventListEdit, target);
        }).on('click', '.item-body', function() {
            const $this = $(this);
            
        }).on('click', '.item-action-bar .fa-arrow-up', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.tree-container');
            const target = $this.closest('.tree-item');
            const prev = target.prev();
            if (prev.length === 0) {
                return;
            }
            const index = target.index();
            const map = $(this).parents('.tree-item').map((_, j) => $(j).index()).toArray();
            const data = that.value;
            EditorTreeControl.moveItem(data, [index, ...map], [index - 1, ...map]);
            prev.before(target);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.item-action-bar .fa-arrow-down', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.tree-container');
            const target = $this.closest('.tree-item');
            const next = target.next();
            if (next.length === 0) {
                return;
            }
            const index = target.index();
            const map = $(this).parents('.tree-item').map((_, j) => $(j).index()).toArray();
            const data = that.value;
            EditorTreeControl.moveItem(data, [index, ...map], [index + 1, ...map]);
            next.after(target);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.item-action-bar .fa-edit', function(e) {
            e.stopPropagation();
            const target = $(this).closest('.tree-item');
            const box = target.closest('.tree-container');
            box.trigger(EditorEventListEdit, target);
        })
        .on('click', '.item-action-bar .fa-plus', function(e) {
            e.stopPropagation();
            const item = $(this).closest('.item-body');
            let next = item.next('.item-children');
            if (next.length === 0) {
                next = $(`<div class="item-children"></div>`);
                item.after(next);
                item.prepend('<div class="item-open-icon"><i class="fa fa-chevron-right"></i></div>');
            }
            const target = $(that.renderTreeItem(that.form));
            next.append(target);
            box.trigger(EditorEventListEdit, target);
        }).on('click', '.item-action-bar .fa-trash', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.tree-container');
            const target = $this.closest('.tree-item');
            const map = [target.index(), ...$(this).parents('.tree-item').map((_, j) => $(j).index()).toArray()];
            target.remove();
            const data = that.value;
            EditorTreeControl.removeItem(data, map);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.item-open-icon', function(e) {
            e.stopPropagation();
            $(this).closest('.tree-item').toggleClass('open');
        }) 
        .on(EditorEventListEdit, function(_, target: HTMLElement|JQuery<HTMLElement>) {
                if (target instanceof HTMLElement) {
                    target = $(target);
                }
                const map = [target.index(), ...$(this).parents('.tree-item').map((_, j) => $(j).index()).toArray()];
                const res = EditorTreeControl.getData(that.value, map);
                that.items.forEach(item => {
                    item.value = res && res[item.name] ? res[item.name] : undefined;
                });
                target.closest('.flip-container').trigger(EditorEventFlipToggle, data => {
                    target.replaceWith(that.renderTreeItem(that.form, data));
                    EditorTreeControl.setData(that.value, map, data);
                }
            );
            });
    }

    private treeInput(input: IVisualInput[], items?: any[]) {
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

    private renderTreeItem(input: IVisualInput[], item?: any) {
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

    public static flipPanel(body: string, form: string = '') {
        return `<div class="flip-container"><div class="flip-front-body">${body}</div><div class="flip-back-body">
            <div class="flip-action-bar">
                <i class="fa fa-arrow-left flip-back-btn"></i>
                <i class="fa fa-save flip-save-btn"></i>
            </div>
            <div class="flip-body">${form}</div>
        </div></div>`;
    }

    public static getData(data: any[], index: number[]): any {
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

    public static setData(data: any[], index: number[], val: any) {
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

    public static moveItem(data: any[], source: number[], dist: number[]) {
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

    public static removeItem(data: any[], index: number[]) {
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
}


class EditorMultipleControl implements IEditorInput, IEditorInputGroup {
    shimmed?: string;
    name: string;
    value: any;
    form: IVisualInput[];
    label?: string;

    private element: JQuery<HTMLElement>;
    private items: IEditorInput[] = [];

    get isUpdated(): boolean {
        return this.value && this.value.length > 0;
    }

    constructor(
        input: IVisualInput
    ) {
        this.value = input.items;
        this.form = input.input;
    }

    reset(): void {
        this.value = [];
        this.element.find('.multiple-container .list-item').remove();
    }
    render(): string {
        const items = [];
        if (this.value) {
            for (const item of this.value) {
                items.push(this.renderMultipleItem(this.form, item));
            }
        }
        this.items = EditorHtmlHelper.renderControlItems(this.form);
        items.push(`<div class="multiple-add-btn">
            <i class="fa fa-plus"></i>
        </div>`);
        return EditorHtmlHelper.input(this, this.label, 
            EditorTreeControl.flipPanel(`<div class="multiple-container">${items.join('')}</div>`, this.items.map(i => i.render()).join(''))
        , 'control-line-group');
    }

    notify(control: IEditorElement): void {}

    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        const that = this;
        const items = box.find('.flip-back-body .flip-body').children();
        this.items.forEach((item, i) => {
            item.ready(items.eq(i), this);
        });
        box// tree 
        .on('click', '.multiple-add-btn', function() {
            const $this = $(this);
            const box = $this.closest('.multiple-container');
            const target = $(that.renderMultipleItem(that.form));
            $this.before(target);
            box.trigger(EditorEventListEdit, target);
        }).on('click', '.item-action-bar .fa-arrow-up', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.multiple-container');
            const target = $this.closest('.list-item');
            const prev = target.prev();
            if (prev.length === 0) {
                return;
            }
            const index = target.index();
            const map = [];
            const data = that.value;
            EditorTreeControl.moveItem(data, [index, ...map], [index - 1, ...map]);
            prev.before(target);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.item-action-bar .fa-arrow-down', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.multiple-container');
            const target = $this.closest('.list-item');
            const next = target.next();
            if (next.length === 0) {
                return;
            }
            const index = target.index();
            const map = [];
            const data = that.value;
            EditorTreeControl.moveItem(data, [index, ...map], [index + 1, ...map]);
            next.after(target);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        }).on('click', '.item-action-bar .fa-edit', function(e) {
            e.stopPropagation();
            const target = $(this).closest('.list-item');
            const box = target.closest('.multiple-container');
            box.trigger(EditorEventListEdit, target);
        }).on('click', '.item-action-bar .fa-trash', function(e) {
            e.stopPropagation();
            const $this = $(this);
            const box = $this.closest('.multiple-container');
            const target = $this.closest('.list-item');
            const map = [target.index()];
            target.remove();
            const data = that.value;
            EditorTreeControl.removeItem(data, map);
            box.closest('.control-line-group').trigger(EditorEventInputChange, data);
        })
        .on(EditorEventListEdit, '.multiple-container', function(_, target: HTMLElement|JQuery<HTMLElement>) {
            if (target instanceof HTMLElement) {
                target = $(target);
            }
            const map = [target.index()];
            const res = EditorTreeControl.getData(that.value, map);
            that.items.forEach(item => {
                item.value = res && res[item.name] ? res[item.name] : undefined;
            });
            target.closest('.flip-container').trigger(EditorEventFlipToggle, data => {
                    target.replaceWith(that.renderMultipleItem(that.form, data));
                    EditorTreeControl.setData(that.value, map, data);
                }
            );
        });
    }

    private renderMultipleItem(input: IVisualInput[], value?: any) {
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
}

class EditorImagesControl implements IEditorInput {
    shimmed?: string;
    name: string;
    label?: string;

    private _value: string[] = [];
    private element: JQuery<HTMLElement>;

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg instanceof Array ? arg : [];
        if (!this.element) {
            return;
        }
        const panel = this.element.find('.image-container');
        panel.find('.image-control-item').remove();
        if (this._value.length < 1) {
            return;
        }
        panel.prepend(this._value.map(item => EditorImageControl.imageItem(item)).join(''));
    }

    public get value(): string[] {
        return this._value;
    }

    get isUpdated(): boolean {
        return this.value && this.value.length > 0;
    }

    constructor(
        input: IVisualInput
    ) {
        this.value = input.items;
    }

    reset(): void {
        this.value = [];
    }
    render(): string {
        const items = [];
        if (this._value) {
            for (const item of this._value) {
                items.push(EditorImageControl.imageItem(item));
            }
        }
        items.push(EditorImageControl.imageUpload(this.shimmed));
        return EditorHtmlHelper.input(this, this.label, 
            EditorHtmlHelper.join('<div class="image-container">', ...items, '</div>')
        , 'control-line-group');
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        const that = this;
        box.on('change', '.drag-control-container input[type=file]', function(this: HTMLInputElement) {
            const target = $(this).closest('.drag-control-container');
            target.addClass('is-uploading');
            that.upload(this.files, url => {
                that.value.push(url);
                target.before(EditorImageControl.imageItem(url));
            }, () => {
                box.trigger(EditorEventInputChange);
                manager.notify(that);
                target.removeClass('is-uploading');
            });
        }).on('click', '.image-control-item .fa-trash', function() {
            const $this = $(this).closest('.image-control-item');
            that.value.splice($this.index(), 1);
            $this.remove();
            box.trigger(EditorEventInputChange);
            manager.notify(that);
        });
    }

    private upload(files: FileList, cb: (url: string) => void, finish: () => void) {
        let i = 0;
        const uploadFn = () => {
            if (i >= files.length) {
                finish();
                return;
            }
            EditorImageControl.upload(files[i], url => {
                if (url) {
                    cb(url);
                }
                i ++;
            });
        };
        uploadFn();
    }
}

class EditorIconControl implements IEditorInput {
    shimmed?: string;
    name: string;
    label?: string;

    private _value: string;
    private element: JQuery<HTMLElement>;
    private iconItems: IItem[] = [
        {name: 'Home', value: 'fa-home'},
        {name: 'Edit', value: 'fa-edit'},
        {name: 'close', value: 'fa-times'},
        {name: 'trash', value: 'fa-trash'},
    ]

    public set value(arg: any) {
        if (this._value === arg) {
            return;
        }
        this._value = arg;
        if (this.element) {
            this.element.find('.icon-option-item').eq(this.indexOf(arg)).addClass('selected').removeClass('hidden').siblings().removeClass('selected');
        }
    }

    public get value(): string {
        return this._value;
    }

    get isUpdated(): boolean {
        return !this.value;
    }

    reset(): void {
        this.value = '';
    }
    render(): string {
        const html = this.iconItems.map(item => `<div class="icon-option-item">
        <i class="fa ${item.value}"></i>
        <span>${item.name}</span>
        </div>`).join('')
        const res = `<div class="control-popup-target">
        <div class="color-icon">
            <i class="fa fa-edit"></i>
        </div>
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
        return EditorHtmlHelper.input(this, this.label, res);
    }
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void {
        this.element = box;
        const that = this;
        box.on('click', '.icon-option-item', function() {
            const $this = $(this);
            $this.addClass('selected').siblings().removeClass('selected');
            that._value = that.iconItems[$this.index()].value;
            box.trigger(EditorEventInputChange);
            manager.notify(that);
        })
        .on('change', '.control-popup .search-header-bar input', function() {
            const $this = $(this);
            const val = $this.val();
            $this.closest('.search-header-bar').next('.search-body').find('.icon-option-item').each(function() {
                const item = $(this);
                const text = item.find('span').text();
                item.toggleClass('hidden', val && text.indexOf(val) < 0);
            });
        });
    }

    private indexOf(icon: string): number {
        for (let i = 0; i < this.iconItems.length; i++) {
            if (this.iconItems[i].value === icon) {
                return i;
            }
        }
        return -1;
    }
}