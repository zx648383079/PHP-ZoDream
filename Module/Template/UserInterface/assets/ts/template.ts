const DEL_URI = 'weight/destroy',
    NEW_URI = 'weight/create',
    REFRESH_URI = 'weight/refresh',
    EDIT_URI = 'weight/save',
    EDIT_DAILOG_URI = 'weight/edit_dialog',
    SETTING_URI = 'weight/setting',
    SAVE_SETTING_URI = 'weight/save_setting',
    INFO_URI = 'weight/info';
class PropertyInput {
    private static _guid = 0;
    public static guid(): number {
        return PropertyInput._guid ++;
    }

    public static title(val: string = '') {
        const id = 'title_' + PropertyInput.guid();
        return PropertyInput.input(id, '标题', PropertyInput.text(id, 'title', val));
    }

    public static lazy(val: number) {
        const id = 'settings_lazy_' + PropertyInput.guid();
        return PropertyInput.input(id, '懒加载', PropertyInput.radio(id, 'settings[lazy]', ['关闭', '开启'], !val ? 0 : val));
    }

    

    public static margin(vals: string[] = []) {
        return PropertyInput.sideInput('settings[style][margin]', '外边距', vals);
    }

    public static padding(name: string, vals: string[] = []) {
        return PropertyInput.sideInput('settings[style][' + name +'][padding]', '内边距', vals);
    }

    public static position(data: any) {
        const html = PropertyInput.positionSide(data?.type, data?.value);
        const option = PropertyInput.option({
            static: '无',
            relative: '相对定位',
            absolute: '绝对定位',
            fixed: '固定定位'
        }, data?.type);
        return PropertyInput.input('', '悬浮', `<select name="settings[style][position][type]">${option}</select><div class="side-input">${html}</div>`, 'position-input');
    }

    public static positionSide(type: string, value?: any) {
        return type && type != 'static' ? PropertyInput.side('settings[style][position][value]', 'settings[style][position][value]', value) : '';
    }

    public static border(name?: string, data?: any) {
        name = 'settings[style]'+ (name ? '[' + name +']' : '') +'[border]';
        const id = PropertyInput.nameToId(name) + '_' + PropertyInput.guid();
        let html = PropertyInput.checkbox(id, name + '[side][]', ['上', '右', '下', '左'], data?.side)
        const option = PropertyInput.option(['实线', '虚线'], data && data.value && data.value[1] == 1 ? 1 : 0)
        return PropertyInput.input(id, '边框', `<input type="text" class="form-control" name="${name}[value][]" value="${data?.value[0]}" placeholder="粗细" size="4"><select name="${name}[value][]">${option}</select><input type="color" name="${name}[value][]" value="${data?.value[2]}"><div class="side-input">${html}</div>
        `);
    }

    public static radius(name?: string, data?: string[]) {
        name = 'settings[style]'+ (name ? '[' + name +']' : '') +'[border-radius]';
        const id = PropertyInput.nameToId(name) + '_' + PropertyInput.guid();
        return PropertyInput.input(id, '圆角', `<input type="text" id="${id}_0" class="form-control " name="${name}[]" value="" size="4" placeholder="左上">
        <input type="text" id="${id}_1" class="form-control " name="${name}[]" value="" size="4" placeholder="右上">
        <br/>
        <input type="text" id="${id}_2" class="form-control " name="${name}[]" value="" size="4" placeholder="左下">
        <input type="text" id="${id}_3" class="form-control " name="${name}[]" value="" size="4" placeholder="右下">`);
    }


    public static color(name?: string, data?: any) {
        name = 'settings[style]'+ (name ? '[' + name +']' : '') +'[color]';
        const id = PropertyInput.nameToId(name) + '_' + PropertyInput.guid();
        return PropertyInput.input(id, '字体颜色', PropertyInput.radio(id, name + '[type]', ['无', '有'], data?.type) + `<input type="color" name="${name}[value]" value="${data?.value}">`);
    }

    public static background(name?: string, data?: any) {
        name = 'settings[style]'+ (name ? '[' + name +']' : '') +'[background]';
        const id = PropertyInput.nameToId(name) + '_' + PropertyInput.guid();
        let html = PropertyInput.radio(id, name+ '[type]', ['无', '颜色', '图片'], '0') + '<div class="value-input">' + PropertyInput.backgroundValue(name, data?.type)+'</div>';
        return PropertyInput.input(id, '背景', html, 'background-input');
    }

    public static backgroundValue(name: string, type?: number) {
        if (!type || type < 1) {
            return '';
        }
        if (type == 2) {
            return `<div class="file-input">
            <input type="text" class="form-control " name="${name}[value]" value="" size="10">
            <button type="button" data-type="upload">上传</button>
        </div>`;
        };
        return `<input type="color" name="${name}[value]">`;
    }

    public static visibility(name: string, val: string|number) {
        name = 'settings[style]['+ name +'][visibility]';
        const id = PropertyInput.nameToId(name) + '_' + PropertyInput.guid();
        
        return PropertyInput.input(id, '可见', PropertyInput.radio(id, name, ['显示', '隐藏'], val));
    }

    public static fontSize(name: string, val?: string) {
        name = 'settings[style]['+ name +'][font-size]';
        const id = PropertyInput.nameToId(name) + '_' + PropertyInput.guid();
        
        return PropertyInput.input(id, '字体大小', PropertyInput.text(id, name, val, 4));
    }

    public static textAlign(name: string, val?: string) {
        name = 'settings[style]['+ name +'][text-align]';
        const id = PropertyInput.nameToId(name) + '_' + PropertyInput.guid();
        
        return PropertyInput.input(id, '字体位置', PropertyInput.radio(id, name, ['居左', '居中', '居右'], !val ? 0 : val));
    }

    public static fontWeight(name: string, val?: string) {
        name = 'settings[style]['+ name +'][font-weight]';
        const id = PropertyInput.nameToId(name) + '_' + PropertyInput.guid();
        
        return PropertyInput.input(id, '字体粗细', PropertyInput.text(id, name, val, 4));
    }



    private static option(items: any, selected: string| number) {
        let html = '';
        $.each(items, function(i: string) {
            const sld = selected == i ? ' selected' : '';
            html += `<option value="${i}"${sld}>${this}</option>`;
        });
        return html;
    }

    private static sideInput(name: string, label: string, vals: string[] = []) {
        const id = PropertyInput.nameToId(name) + '_' + PropertyInput.guid();
        return PropertyInput.input(id, label, PropertyInput.side(id, name, vals), 'side-input');
    }

    private static side(id: string, name: string, vals: string[] = []) {
        let html = '';
        $.each(['上', '右', '下', '左'], function(i: number) {
            const val = vals && vals.length > i ? vals[i] : '';
            html += `<input type="text" id="${id}_${i}" class="form-control " name="${name}[]" size="4" value="${val}" placeholder="${this}">`;
        });
        return html;
    }

    private static radio(id: string, name: string, items: any, selected: string| number) {
        let html = '';
        let j = 0;
        $.each(items, function(i: string) {
            const index = [id, j ++].join('_');
            const chk = i == selected ? ' checked' : '';
            html += `<span class="radio-label"><input type="radio" id="${index}" name="${name}" value="${i}"${chk}><label for="${index}">${this}</label></span>`;
        });
        return html;
    }

    private static checkbox(id: string, name: string, items: any, val: string[] = []) {
        let html = '';
        let j = 0;
        $.each(items, function(i: string) {
            const index = [id, j ++].join('_');
            const chk = val && val.indexOf(i) >= 0 ? ' checked' : '';
            html += `<span class="check-label"><input type="checkbox" id="${index}" name="${name}" value="${i}"${chk}><label for="${index}">${this}</label></span>`;
        });
        return html;
    }

    private static text(id: string, name: string, val: string = '', size?: number) {
        const option = size ? ` size="${size}"` : '';
        if (!val) {
            val = '';
        }
        return `<input type="text" id="${id}" class="form-control" name="${name}" value="${val}"${option}>`;
    }

    private static input(id: string, name: string, content: string, cls: string = ''): string {
        return `<div class="input-group"><label for="${id}">${name}</label><div class="${cls}">${content}</div></div>`;
    }

    private static nameToId(name: string) {
        return name.replace(/\[/g, '_').replace(/\]/g, '');
    }
}


class Weight {
    constructor(
        public box: JQuery
    ) {
        
    }

    public toggle(state?: boolean) {
        this.box.toggleClass('weight-edit-mode', state);
        return this;
    }

    public toggleLoading(state?: boolean) {
        this.box.toggleClass('weight-loading', state);
        return this;
    }

    /**
     * html
     */
    public html(html?: string) {
        const newBox = $(html);
        this.box = this.box.replaceWith(newBox);
        this.box = newBox;
        return this;
    }

    /**
     * id
     */
    public id(): number {
        return parseInt(this.box.attr('data-id'));
    }

    /**
     * weightId
     */
    public weightId(): number {
        return parseInt(this.box.attr('data-weight'));
    }
}

class Page {
    constructor(
        public id: number,
        public baseUri: string = BASE_URI
    ) {
        this.box = $("#page-box");
        this.element = $('#mainMobile');
        this.bodyBox = $('#mainGrid');
        this.panelGroup = this.box.find('.panel-group');
        this.editDialog = $('#edit-dialog').dialog();
        let that = this;
        const iframe = this.bodyBox[0] as any;
        const ready = function() {
            that.body = that.bodyBox.contents().find('body') as JQuery;
            that._init();
        }
        if (iframe.attachEvent) {
            iframe.attachEvent('onload', ready); 
        } else {
            iframe.onload = ready;
        }
    }

    public editDialog: any;

    public box: JQuery;

    public element: JQuery;

    public panelGroup: JQuery;

    public bodyBox: JQuery;

    public body: JQuery;

    public weight: Weight;

    private _cacheSettings: any;

    private _init() {
        this._bindEvent();
        this.resize();
    }

    private _bindEvent() {
        let that = this;
        $(window).on('resize', function() {
            that.resize();
        });
        this.body.on("click", ".weight-action .del", function() {
            that.removeWeight($(this).closest('.weight-edit-grid'));
        }).on("click", ".weight-action .edit", function(e) {
            e.stopPropagation();
            that.setWeight($(this).parents('.weight-edit-grid'));
            that.showEditDialog();
        }).on("click", ".weight-action .refresh", function(e) {
            e.stopPropagation();
            that.refreshWeight($(this).closest('.weight-edit-grid'));
        }).on("click", ".weight-action .property", function(e) {
            e.stopPropagation();
            that.setWeight($(this).closest('.weight-edit-grid'));
            that.showPropertyPanel();
        }).on('click', 'a', function(e) {
            e.preventDefault();
        });
        this.panelGroup.on('click', '.panel-item .panel-header .fa-close', function(e) {
            e.stopPropagation();
            let box = $(this).closest('.panel-item');
            box.addClass('min');
            that.resize();
        }).on('click', '.panel-item .panel-header', function() {
            let box = $(this).closest('.panel-item');
            if (box.hasClass('min')) {
                box.removeClass('min').siblings().addClass('min');
                that.resize();
            }
        }).on('click', '.expand-box .expand-header', function() {
            $(this).closest('.expand-box').toggleClass('open');
        }).on('change', '.position-input select', function() {
            let $this = $(this);
            $this.next().html(PropertyInput.positionSide($this.val() as string));
        }).on('click', '.background-input input[type="radio"]', function() {
            let $this = $(this);
            $this.closest('.background-input').find('.value-input').html(PropertyInput.backgroundValue($this.attr('name').replace('[type]', ''), $this.val() as number));
        }).on('change', 'input,textarea', function() {
            that.autoSave();
        }).on('click', '.style-item', function() {
            $(this).addClass('active').siblings().removeClass('active');
            that.autoSave();
        }).find('.weight-edit-grid').attr('draggable', 'true').on('dragstart', function(e) {
            e.originalEvent.dataTransfer.setData("Text", e.target.id);
            weight = $(this);
        });
        let weight: JQuery = null;
        this.body.on('dragover', '.weight-row', function(e) {
            e.stopPropagation();
            e.preventDefault();
        }).on('drop', '.weight-row', function(e) {
            e.stopPropagation();
            e.preventDefault();
            
            that.addWeight(weight.clone(), $(this));
        });
        this.editDialog.box.on('click', '.multiple-box .multiple-add-btn', function() {
            let $this = $(this);
            $this.before($this.prev('.item').clone());
        }).on('click', '.multiple-box .multiple-del-btn', function() {
            let $this = $(this);
            if ($this.closest('.multiple-box').find('.item').length > 1) {
                $this.closest('.item').remove();
            }
        });
        this.editDialog.on('done', function() {
            that.post(EDIT_URI, that.editDialog.find('.dialog-body').serialize(), function(res) {
                if (res.code !== 200) {
                    return;
                }
                that.showPropertyPanel();
                that.weight.html(res.data.html);
            })
            this.close();
        });
        this.bindRule();
    }

    private autoSave() {
        const data = formData(this.panelGroup.find('.form-table')) + '&theme_style_id=' + this.panelGroup.find('.style-item.active').attr('data-id') + '&id=' + this.weight.id();
        this.post(EDIT_URI, data, res => {
            if (res.code !== 200) {
                return;
            }
            this.weight.html(res.data.html);
        });
    }

    public showEditDialog() {
        let that = this;
        this.post(EDIT_DAILOG_URI, {
            id: this.weight.id()
        }, function(data) {
            if (data.code !== 200) {
                return;
            }
            that.editDialog.find('.dialog-body').html('<input type="hidden" name="id" value="'+ that.weight.id() +'">' + data.data.html);
            that.editDialog.show();
        });
    }


    public showPropertyPanel() {
        this.post(SETTING_URI, {
            id: this.weight.id()
        }, res => {
            if (res.code !== 200) {
                return;
            }
            const data = res.data;
            const styles = data.settings?.style;
            let items = this.panelGroup.find('.form-table .tab-item');
            items[0].innerHTML = PropertyInput.title(data.title) + PropertyInput.lazy(data.settings?.lazy);
            let boxes = $(items[1]).find('.expand-body');
            boxes[0].innerHTML = PropertyInput.margin(styles?.margin)
                            + PropertyInput.position(styles?.position)
                            + PropertyInput.border(null, styles?.border) 
                            + PropertyInput.radius(null, data.settings ? data.settings['border-radius'] : null)
                            + PropertyInput.color(null, data.settings?.color)
                            + PropertyInput.background(null, data.settings?.background);
            boxes[1].innerHTML = PropertyInput.visibility('title', styles?.title?.visibility)
                            + PropertyInput.padding('title', styles?.title?.padding)
                            + PropertyInput.border('title', styles?.title?.border) 
                            + PropertyInput.radius('title', styles?.title['border-radius'])
                            + PropertyInput.color('title', styles?.title?.color)
                            + PropertyInput.fontSize('title', styles?.title['font-size'])
                            + PropertyInput.fontWeight('title', styles?.title['font-weight'])
                            + PropertyInput.textAlign('title', styles?.title['text-align'])
                            + PropertyInput.background('title', styles?.title?.background);
            boxes[2].innerHTML = PropertyInput.visibility('content', styles?.content?.visibility)
                            + PropertyInput.padding('content', styles?.content?.padding)
                            + PropertyInput.border('content', styles?.content?.border) 
                            + PropertyInput.radius('content', styles?.content['border-radius'])
                            + PropertyInput.color('content', styles?.content?.color)
                            + PropertyInput.fontSize('content', styles?.content['font-size'])
                            + PropertyInput.fontWeight('content', styles?.content['font-weight'])
                            + PropertyInput.textAlign('content', styles?.content['text-align'])
                            + PropertyInput.background('content', styles?.content?.background);
            this.panelGroup.find('.style-item').each(function() {
                let $this = $(this);
                $this.toggleClass('active', $this.attr('data-id') == data.theme_style_id);
            });
            this.showPanel('property');
        });
    }

    /**
     * showPanel
     */
    public showPanel(name: string): JQuery {
        let panel = this.panelGroup.find('[data-panel=' + name +']');
        panel.removeClass('min').siblings().addClass('min');
        return panel;
    }

    /**
     * bindRule
     */
    public bindRule() {
        let line: JQuery,
            mode: number = 0;
        let ruleBox = this.element.find('.rule-box').on('mousedown', '.rule-lines div', function(e) {
            e.stopPropagation();
            mode = 0;
            line = $(this);
        }).on('mousedown', '.top-rule', function() {
            mode = 1;
        }).on('mousemove', '.top-rule', function() {
            if (mode === 1) {
                line = $('<div class="h-line"></div>');
                ruleBox.find('.rule-lines').append(line);
                mode = 0;
            }
        }).on('mousedown', '.left-rule', function() {
            mode = 2;
        }).on('mousemove', '.left-rule', function() {
            if (mode === 2) {
                line = $('<div class="v-line"></div>');
                ruleBox.find('.rule-lines').append(line);
                mode = 0;
            }
        });
        $(document).on('mousemove', function(e: any) {
            if (!line) {
                return;
            }
            if (line.hasClass('v-line')) {
                line.css('left', e.clientX + 'px');
                return;
            }
            let top = e.clientY - ruleBox.offset().top;
            line.css('top', top + 'px');
        }).on('mouseup', function() {
            if (!line) {
                return;
            }
            if (line.hasClass('v-line')) {
                line.offset().left < 20 && line.remove();
            } else {
                (line.offset().top - ruleBox.offset().top) < 20 && line.remove();
            }
            line = undefined;
            
            
        });
    }

    /**
     * refreshWeight
     */
    public refreshWeight(element?: JQuery) {
        if (!element && !this.weight) {
            return;
        }
        if (!element) {
            const weight = this.weight.toggleLoading(true);
            this.post(REFRESH_URI, {
                id: weight.id()
            }, function(data) {
                weight.toggleLoading(false);
                if (data.code == 200) {
                    weight.html(data.data.html);
                }
            });
            return;
        }
        let id = element.attr('data-id'),
            weight = this.setWeight(element, false).toggleLoading(true);
        this.post(REFRESH_URI, {
            id: id
        }, function(data) {
            weight.toggleLoading(false);
            if (data.code == 200) {
                weight.html(data.data.html);
            }
        });
    }

    public setWeight(element: JQuery, withEdit: boolean = true): Weight {
        if (this.weight) {
            this.weight.toggle(false);
        }
        this.weight = new Weight(element);
        this.weight.toggle(true);
        if (withEdit) {
            
        }
        return this.weight;
    }

    public removeWeight(element: JQuery) {
        this.post(DEL_URI, {
            id: element.attr('data-id')
        }, function(data) {
            if (data.code == 200) {
                element.remove();
            }
        });
    }

    public addWeight(element: JQuery, parent: JQuery): Weight {
        let that = this;
        parent.append(element);
        element.width('auto');
        let weight = this.setWeight(element).toggleLoading(true);
        this.post(NEW_URI, {
            weight_id: element.attr('data-weight'),
            parent_id: parent.attr('data-id')
        }, function(data) {
            weight.toggleLoading(false);
            if (data.code == 200) {
                weight.html(data.data.html);
            }
        });
        return weight;
    }

    public resize() {
        let width = 16;
        this.panelGroup.find('.panel-item').each(function() {
            if (!$(this).hasClass('min')) {
                width = 16 * 15;
            }
        });
        this.panelGroup.width(width);
        this.panelGroup.find('.panel-body .tab-body').height(this.panelGroup.height() - 60)
        this.box.css('padding-left', width + 'px');
        const height = $(window).height() - this.box.offset().top;
        this.box.height(height);
        const isMobile = !!this.element.attr('class');
        if (!isMobile) {
            this.element.height(height - 25);
        }
        const top = isMobile ? this.bodyBox.offset().top - this.element.offset().top - 20 : 0;
        const left = isMobile ? this.bodyBox.offset().left - this.element.offset().left - 20 : 0;
        this.drawRule(this.element.find('.top-rule').css('top', top + 'px'), left + 20);
        this.drawRule(this.element.find('.left-rule').css('left', left + 'px'), top + 20);
        this.bodyBox.width(this.element.width() - 20);
        this.bodyBox.height(this.element.height() - 20);
    }

    public html(): string {
        return $.htmlClean(this.body.html(), {
            format: true,
            allowedAttributes: [
                ["id"],
                ["class"],
                ["data-toggle"],
                ["data-target"],
                ["data-parent"],
                ["role"],
                ["data-dismiss"],
                ["aria-labelledby"],
                ["aria-hidden"],
                ["data-slide-to"],
                ["data-slide"]
            ]
        });
    }

    /**
     * getSetting
     */
    public getSetting(id: number, cb: (data: any) => void) {
        let that = this;
        if (this._cacheSettings.hasOwnProperty(id)) {
            cb && cb(this._cacheSettings[id]);
            return this;
        }
        this.post(SETTING_URI, {
            id: id
        }, function(data) {
            if (data.code == 200) {
                that._cacheSettings[id] = data.data;
                cb && cb(data.data);
            }
        });
        return this;
    }

    /**
     * saveSetting
     */
    public saveSetting(id: number, args: any, cb: (data: any) => void) {
        let that = this;
        this.post(SAVE_SETTING_URI, {
            id: id,
            setting: args
        }, function(data) {
            if (data.code == 200) {
                that._cacheSettings[id] = data;
                cb && cb(data.data);
            }
        });
        return this;
    }

    /**
     * postJson
     */
    public post(path: string, data: any, cb: any) {
        if (typeof data === 'string') {
            data += '&page_id=' + this.id;
        } else {
            data['page_id'] = this.id;
        }
        postJson(this.baseUri + path, data, cb);
        return this;
    }

    /**
     * drawRule
     */
    public drawRule(box: JQuery, start: number, scale: number = 1) {
        let width = box.width(), height = box.height();
        let canvas: HTMLCanvasElement = box[0] as HTMLCanvasElement;
        canvas.width = width;
        canvas.height = height;
        let context = canvas.getContext('2d');
        context.clearRect(0, 0, canvas.width, canvas.height);
        let direct = canvas.width > canvas.height; // true 横向
        let length = direct ? canvas.width : canvas.height;
        
        for (let i = start; i < length; i+= 10) {
            let real = i - start;
            let len = real % 50 === 0 ? 10 : 5;
            this.drawLine(context, direct,  i, len, len > 5 ? real.toString() : undefined);
        }
    }

    private drawLine(context: CanvasRenderingContext2D, direct: boolean, i: number, length: number, tip?: string) {
        if (direct) {
            context.moveTo(i, 20 - length);
            context.lineTo(i, 20);
        } else {
            context.moveTo(20 - length, i);
            context.lineTo(20, i);
        }
        context.lineWidth = 1;
        context.strokeStyle = "red";
        context.stroke();
        if (!tip) {
            return;
        }
        context.font = '6px Microsoft YaHei';
        if (direct) {
            context.fillText(tip, i- 5, 10);
        } else {
            context.fillText(tip, 0, i + 3);
        }
        
    }
}


function bindPage(pageId: number) {
    let page = new Page(pageId);
    $(".mobile-size li").click(function() {
        $(".mobile-size").parent().removeClass("open");
        let size = $(this).attr("data-size").split("*");
        page.element.removeClass().removeAttr('style').addClass('mobile-' + size[0]);
        page.resize();
    });
    $(".navbar>li>div").click(function() {
        $(this).parent().toggleClass("open");
    });
    $(".mobile-size li").click(function() {
        $(this).addClass("active").siblings().removeClass("active");
    });

    $(".mobile-rotate").click(function() {
        page.element.toggleClass('rotate');
    });
    $(".expand>.head").click(function() {
        $(this).parent().toggleClass("open");
    });
    if ($(window).width() > 769) {
        $('.sidebar-container-toggle').trigger('click');
    }
}

function bindEdit() {
    $('.theme-select').on('click', '.theme-item', function() {
        $(this).addClass('active').siblings().removeClass('active');
        $('input[name=theme_id]').val($(this).data('id'));
    });
}

class SearchDailog {
    constructor(
        element: string
    ) {
        this.dialog = $(element).dialog({});
        $.get(this.dialog.box.data('url'), {
            selected: this.selected.join(',')
        }, html => {
            this.html(html);
        });
        this.bindEvent();
    }

    public dialog: any;
    public selected: number[] = [];
    private _selected: number[] = [];
    private _doneCallback: Function;

    private bindEvent() {
        let that = this;
        this.on('submit', '.dialog-search form', function() {
            let $this = $(this);
            $.get($this.attr('action'), $this.serialize() + '&selected=' + that.selected.join(','), html => {
                that.html(html);
            });
            return false;
        }).on('click', '.dialog-body-box .item', function() {
            let $this = $(this).toggleClass('selected');
            let id = parseInt($this.data('id'), 10);
            that.toggleItem(id, $this.hasClass('selected'));
        }).on('click', '.dialog-pager a', function(e) {
            e.preventDefault();
            $.get($(this).attr('href'), html => {
                that.html(html);
            });
        });
        this.dialog.on('done', () => {
            this.selected = [...this._selected];
            this.dialog.close();
            this._doneCallback && this._doneCallback.call(this, this.selected);
        });
    }

    /**
     */
    public toggleItem(id: number, has?: boolean) {
        if (id < 1) {
            return;
        }
        let index = this._selected.indexOf(id);
        if (typeof has === 'undefined') {
            has = index < 0;
        }
        if (has) {
            if (index < 0) {
                this._selected.push(id);
            }
            return;
        }
        if (index >= 0) {
            this._selected = this._selected.splice(index, 1);
        }
    }

    /**
     * html
     */
    public html(html: string) {
        if (html.indexOf('dialog-body-box') > 0) {
            this.find('.dialog-body').html(html);
        } else {
            this.find('.dialog-body .dialog-body-box').html(html);
        }
        this.dialog.resize();
        return this;
    }

    public find(tag: string): JQuery {
        return this.dialog.find(tag);
    }

    public on(event: string, tag: string | Function, cb?: (event: JQueryEventObject) => void) {
        if (event === 'done') {
            this._doneCallback = tag as Function;
            return this;
        }
        this.dialog.box.on(event, tag, cb);
        return this;
    }

    /**
     * show
     */
    public show() {
        this._selected = [...this.selected];
        this.find('.dialog-body-box .item').each((_, item) => {
            let $this = $(item);
            $this.toggleClass('selected', this._selected.indexOf($this.data('id')) >= 0);
        });
        this.dialog.show();
    }
}

function bindNewTheme() {
    let box = new SearchDailog('.theme-dialog');
    $('*[data-type="add"]').click(function() {
        box.show();
    });
    box.on('done', (selected: number[]) => {
        
    });
}