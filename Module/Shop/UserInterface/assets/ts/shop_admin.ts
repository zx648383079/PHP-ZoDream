let BASE_URI: string;
class Attribute {
    /**
     *
     */
    constructor(
        public element: JQuery,
        public goodsId?: number
    ) {
        this.attrBox = this.element.find('.attr-box');
        this.productBox = this.element.find('.product-table');
        this.bindEvent();
    }

    private attrBox: JQuery;
    private productBox: JQuery;

    public attrList: Array<any>;

    public productList: Array<any>;

    private bindEvent() {
        let that = this;
        this.attrBox.on('click', '.add-box .btn', function() {
            let box = $(this).closest('.input-group'), 
                group_id = box.attr('data-id'),
                value = '',
                price = 0;
            box.find('.add-box input').each((i, item: HTMLInputElement) => {
                if (i < 1) {
                    value = item.value;
                } else {
                    price = parseFloat(item.value);
                }
                item.value = '';
            });
            that.addAttr(group_id, value, price);
        }).on('click', '.check-label input[type=checkbox]', function() {
            let box = $(this).closest('.input-group'), 
                group_id = box.attr('data-id'),
                is_checked = $(this).is(':checked'),
                value = '';
            $(this).next().find('input').each((i, item: HTMLInputElement) => {
                if (i < 1) {
                    value = item.value;
                }
            });
            that.toggleAttr(group_id, value, is_checked);
        }).on('click', '.fa-remove', function(e) {
            e.stopPropagation();
            let box = $(this).closest('.input-group'), 
                group_id = box.attr('data-id'),
                attr_id = $(this).closest('.check-label').data('id');
            that.deleteAttrById(group_id, attr_id);
        }).on('propertychange change', 'input[type=text],select', function() {
            let $this = $(this);
            if ($this.closest('.add-box').length > 0) {
                return;
            }
            that.editAttr($this.attr('name'), $this.val());
        });
        this.element.on('click', '.batch-box .btn', function() {
            let formData = {};
            $(this).closest('.batch-box').find('input').each(function () {
                let $this = $(this)
                    , formType = $this.data('type')
                    , value = $this.val();
                if (typeof formType !== 'undefined' && formType !== '' && value !== '') {
                    formData[formType] = value;
                }
            });
            if (!$.isEmptyObject(formData)) {
                that.productList.forEach(function (item, index) {
                    that.productList[index].form = $.extend({},that.productList[index].form, formData);
                });
                // 渲染商品规格table
                that.renderProduct();
            }
        });
        this.productBox.on('propertychange change', 'input', function() {
            let $this = $(this)
            , dataType = $this.attr('name')
            , specIndex = $this.parent().parent().data('index');
            that.productList[specIndex].form[dataType] = $this.val();
        });
    }

    public editAttr(name, value) {
        let that = this,
            data = {
                goods_id: this.goodsId
            };
        data[name] = value;
        postJson(BASE_URI + 'goods/edit_attribute', data, function(data) {
            if (data.code != 200) {
                parseAjax(data);
                return;
            }
            that.setAttr(data.data);
            that.refresh();
        });
    }

    private setAttr(item) {
        let group = this.getGroupById(item.attribute_id);
        if (group.type < 0) {
            group.attr_items = [item];
            return;
        }
        for (let i = 0; i < group.attr_items.length; i++) {
            if (group.attr_items[i].id == item.id || group.attr_items[i].value == item.value) {
                group.attr_items[i] = $.extend({}, group.attr_items[i], item);
                return;
            }
        }
        group.attr_items.push(item);
    }

    public deleteAttrById(group_id, attr_id) {
        let group = this.getGroupById(group_id);
        if (group.input_type == 1) {
            return;
        }
        let that = this;
        postJson(BASE_URI + 'goods/delete_attribute', {
            goods_id: this.goodsId,
            id: attr_id
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            that.deleteAttrListById(group, attr_id);
        });
    }

    public toggleAttr(group_id, value, is_checked = false) {
        let group = this.getGroupById(group_id);
        if (group.input_type != 1) {
            return;
        }
        let that = this;
        if (!is_checked) {
            postJson(BASE_URI + 'goods/delete_attribute', {
                goods_id: this.goodsId,
                attribute_id: group_id,
                value: value,
            }, function(data) {
                if (data.code != 200) {
                    return;
                }
                that.deleteAttr(group, value);
            });
            return;
        }
        postJson(BASE_URI + 'goods/save_attribute', {
            goods_id: this.goodsId,
            attribute_id: group_id,
            value: value,
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            group.attr_items.push(data.data);
            that.refresh();
        });
    }

    public deleteAttr(group, value) {
        for (let index = 0; index < group.attr_items.length; index++) {
           if (group.attr_items[index].value == value) {
                group.attr_items.splice(index, 1)
                break;
           }
        }
        this.refresh();
    }

    public deleteAttrListById(group, id) {
        for (let index = 0; index < group.attr_items.length; index++) {
           if (group.attr_items[index].id == id) {
                group.attr_items.splice(index, 1)
                break;
           }
        }
        this.refresh();
    }

    public addAttr(group_id, value, price = 0) {
        let group = this.getGroupById(group_id),
            attr = this.getAttrItem(value, group.attr_items);
        if (attr) {
            return;
        }
        let that = this;
        postJson(BASE_URI + 'goods/save_attribute', {
            goods_id: this.goodsId,
            attribute_id: group_id,
            value: value,
            price: price
        }, function(data) {
            if (data.code != 200) {
                return;
            }
            group.attr_items.push(data.data);
            that.refresh();
        });
    }

    private getGroupById(id) {
        for (let i = 0; i < this.attrList.length; i++) {
            if (this.attrList[i].id == id) {
                return this.attrList[i];
            }
        }
    }

    public refresh() {
        this.renderHtml();
    }

    public renderHtml() {
        this.renderAttr();
        
        this.renderProduct();
    }

    public renderProduct() {
        if (this.attrList.length < 1) {
            this.productBox.empty().parent().hide();
            return;
        }
        let [attr_list, product_list] = this.buildAttrList();
        if (attr_list.length < 1) {
            this.productBox.empty().parent().hide();
            return;
        }
        this.productBox.html(template('tpl_spec_table', {
            attr_list: attr_list,
            product_list: product_list
        })).parent().show();
    }

    public renderAttr() {
        this.element.toggle(this.attrList.length > 0);
        if (this.attrList.length < 1) {
            return;
        }
        let html = '';
        this.attrList.forEach(item => {
            let item_html = '';
            if (item.type < 1) {
                item_html = this.getOnlyAttr(item);
            } else if (item.type == 1) {
                item_html = this.getSingleAttr(item);
            } else {
                item_html = this.getMutliAttr(item);
            }
            html += '<div class="input-group" data-id="'+ item.id +'"><label>'+ item.name+ '</label><div>' + item_html + '</div></div>';
        });
        this.attrBox.html(html);
    }

    private getMutliAttr(item) {
        let html = '';
        if (item.input_type == 1) {
            item.default_value.forEach((value, i) => {
                let attr = this.getAttrItem(value, item.attr_items),
                    id = attr ? attr.id : '';
                html += '<span class="check-label attr-block" data-id="'+id+'"><input type="checkbox" id="attr_'+ item.id +'_'+ i +'" name="attr['+item.id+']['+id+'][checked]" value="1" '+ (id ? 'checked' : '') +'><label for="attr_'+ item.id +'_'+ i +'"><input type="text" name="attr['+item.id+']['+id+'][value]" value="'+ value +'" readonly><input type="text" name="attr['+item.id+']['+id+'][price]" value="'+ (attr ? attr.price : '') +'" placeholder="价格"></label></span>';
            });
            return html;
        }
        item.attr_items.forEach((attr, i) => {
            html += '<span class="check-label attr-block" data-id="'+attr.id+'"><input type="checkbox" id="attr_'+ item.id +'_'+ i +'" name="attr['+item.id+']['+attr.id+'][checked]" value="1" checked><label for="attr_'+ item.id +'_'+ i +'"><input type="text" name="attr['+item.id+']['+attr.id+'][value]" value="'+ attr.value +'"><input type="text" name="attr['+item.id+']['+attr.id+'][price]" value="'+ (attr.price ? attr.price : 0) +'" placeholder="价格"></label><i class="fa fa-remove"></i></span>';
        });
        return html + '<div class="add-box attr-block"><input type="text"><input type="text" placeholder="价格"><button type="button" class="btn">添加</button></div>';
    }

    private getSingleAttr(item) {
        let html = '';
        if (item.input_type == 1) {
            item.default_value.forEach((value, i) => {
                let id = this.getAttrId(value, item.attr_items);
                html += '<span class="check-label" data-id="'+id+'"><input type="checkbox" id="attr_'+ item.id +'_'+ i +'" name="attr['+item.id+']['+id+'][checked]" value="1" '+ (id ? 'checked' : '') +'><label for="attr_'+ item.id +'_'+ i +'"><input type="text" name="attr['+item.id+']['+id+'][value]" value="'+ value +'" readonly></label></span>';
            });
            return html;
        }
        item.attr_items.forEach((attr, i) => {
            html += '<span class="check-label" data-id="'+attr.id+'"><input type="checkbox" id="attr_'+ item.id +'_'+ i +'" name="attr['+item.id+']['+attr.id+'][checked]" value="1" checked><label for="attr_'+ item.id +'_'+ i +'"><input type="text" name="attr['+item.id+']['+attr.id+'][value]" value="'+ attr.value +'"></label><i class="fa fa-remove"></i></span>';
        });
        return html + '<div class="add-box"><input type="text"><button type="button" class="btn">添加</button></div>';
    }

    private getAttrId(value, attr_list) {
        for (let i = 0; i < attr_list.length; i++) {
            if (attr_list[i].value == value) {
                return attr_list[i].id;
            }
        }
        return '';
    }

    private getAttrItem(value, attr_list) {
        for (let i = 0; i < attr_list.length; i++) {
            if (attr_list[i].value == value) {
                return attr_list[i];
            }
        }
    }

    private getOnlyAttr(item) {
        let id = '', value = '';
        if (item.attr_items.length > 0) {
            id = item.attr_items[0].id;
            value = item.attr_items[0].value;
        }
        if (item.input_type == 1) {
            return '<select class="form-control" name="attr['+item.id+']['+id+'][value]">'+ this.getSelectAttrHtml(item.default_value, value) +'</select>';
        }
        return '<input type="text" class="form-control" name="attr['+item.id+']['+id+'][value]" value="'+ value +'">';
    }

    private getSelectAttrHtml(data: string[], selected: string): string {
        var html = '';
        $.each(data, function() {
            html += '<option value="'+ this +'"'+ (this == selected ? 'selected' : '') +'>'+ this +'</option>';
        });
        return html;
    }

    public refreshByGroup(id: number) {
        if (id < 0) {
            this.clear();
            return;
        }
        let that = this;
        $.getJSON(BASE_URI + "goods/attribute", {
            group_id: id,
            goods_id: this.goodsId
        }, function(data) {
            if (data.code == 200) {
                that.attrList = data.data.attr_list;
                that.productList = data.data.product_list;
                that.refresh();
            }
        });
    }

    public clear() {
        this.html("");
    }

    public html(html?: string) {
        return this.element.html(html);
    }

    private getRadioAttr() {
        let attr_list = [];
        for (let i = 0; i < this.attrList.length; i++) {
            if (this.attrList[i].type == 1) {
                attr_list.push(this.attrList[i]);
            }
        }
        return attr_list;
    }

    private buildAttrList() {
        // 规格组合总数 (table行数)
        let totalRow = 1,
            i,
            attr_list = this.getRadioAttr();
        for (i = 0; i < attr_list.length; i++) {
            totalRow *= attr_list[i].attr_items.length;
        }
        // 遍历tr 行
        let spec_list = [];
        for (i = 0; i < totalRow; i++) {
            let rowData = [], rowCount = 1, specSkuIdAttr = [];
            // 遍历td 列
            for (let j = 0; j < attr_list.length; j++) {
                let skuValues = attr_list[j].attr_items;
                rowCount *= skuValues.length;
                let anInterBankNum = (totalRow / rowCount)
                    , point = ((i / anInterBankNum) % skuValues.length);
                if (0 === (i % anInterBankNum)) {
                    rowData.push({
                        rowspan: anInterBankNum,
                        item_id: skuValues[point].id,
                        spec_value: skuValues[point].value
                    });
                }
                specSkuIdAttr.push(skuValues[parseInt(point.toString())].id);
            }
            spec_list.push({
                attributes: specSkuIdAttr.sort().join('_'),
                rows: rowData,
                form: {}
            });
        }
        // 合并旧sku数据
        if (this.productList.length > 0 && spec_list.length > 0) {
            for (i = 0; i < spec_list.length; i++) {
                let overlap = this.productList.filter(function (val) {
                    return val.attributes === spec_list[i].attributes;
                });
                if (overlap.length > 0) spec_list[i].form = overlap[0].hasOwnProperty('form') ? overlap[0].form : overlap[0];
            }
        }
        this.productList = spec_list;
        return [attr_list, spec_list];
    }
}

function bindGoods(baseUri: string, goodsId: number) {
    BASE_URI = baseUri;
    UE.delEditor('container');
    let ue = UE.getEditor('container'),
        attr = new Attribute($(".attribute-box"), goodsId);
    $("#attribute_group_id").change(function() {
        attr.refreshByGroup(parseInt($(this).val() + ''));
    }).trigger('change');
    $(".btn-save").click(function() {
        $("input[name=product]").val(JSON.stringify(attr.productList));
        $(this).closest('form').submit();
    });
    $('.multi-image-box .add-item').upload({
        grid: '.multi-image-box',
        url: '/ueditor.php?action=uploadimage',
        name: 'upfile',
        multiple: true,
        isAppend: false,
        allowMultiple: true,
        removeTag: '.fa-times',
        template: '<div class="image-item"><img src="{url}" alt=""><input type="hidden" name="gallery[]" value="{url}"><i class="fa fa-times"></i></div>',
        onafter: function(data) {
            return data.state == 'SUCCESS' ? data : false;
        }
    });
}

///

class RegionalChoice {
    constructor(
        public element: JQuery,
        public datas: any
    ) {
        this.initInterface();
    }

    /**
     * 条件渲染
     * @param alreadyIds 已存在的区域ID: 用于新增
     * @param checkedIds 已选中的区域ID: 用于编辑
     * @param
     */
    public render(alreadyIds, checkedIds?: any) {
        this.initInterface();
        alreadyIds = alreadyIds || [];
        alreadyIds.length > 0 && this.setAlready(alreadyIds);
        checkedIds = checkedIds || [];
        checkedIds.length > 0 && this.setChecked(checkedIds);
    }

    /**
     * 初始化地域界面
     */
    public initInterface() {
        let _this = this;
        _this.element.empty().append(
            $('<div/>', {
                class: 'place-div'
            }).append(
                $('<div/>', {}).append(
                    $('<div/>', {
                        class: 'checkbtn'
                    })
                        .append(
                            $('<label/>', {})
                            // 全选框
                                .append(
                                    $('<input/>', {
                                        type: 'checkbox',
                                        change: function () {
                                            let checked = $(this).is(':checked'),
                                                $allCheckbox = $('.place').find('input[type=checkbox]');
                                            $('.ratio').html('');
                                            $allCheckbox.prop('checked', checked);
                                        }
                                    })
                                )
                                .append(' 全国')
                        )
                        .append(
                            $('<a/>', {
                                class: 'clearCheck',
                                text: '清空',
                                click: function () {
                                    _this.destroy();
                                }
                            })
                        )
                ).append(
                    // 省份
                    $('<div/>', {
                        class: 'place clearfloat'
                    }).append(function () {
                        return _this.getSmallPlace();
                    }())
                )
            )
        );

    }

    /**
     * 遍历省份
     * @returns {jQuery}
     * @constructor
     */
    public getSmallPlace() {
        let _this = this;
        return $('<div/>', {
            class: 'smallplace clearfloat'
        }).append(
            $.map(_this.datas, function (item) {
                return $('<div/>', {
                    class: 'place-tooltips'
                })
                    .append(
                        $('<label/>', {})
                            .append(
                                $('<input/>', {
                                    id: 'region_' + item.id,
                                    type: 'checkbox',
                                    class: 'province',
                                    change: function () {
                                        let $this = $(this)
                                            , small = $this.parent().next('.citys').find('input')
                                            , $placeTooltips = $this.parents('.place-tooltips');
                                        if ($this.prop('checked')) {
                                            small.prop('checked', true);
                                            $placeTooltips.find('.ratio').html('(' + small.length + '/' + small.length + ')');
                                        } else {
                                            small.prop('checked', false);
                                            $placeTooltips.find('.ratio').html('');
                                        }

                                    }
                                })
                            )
                            .append(
                                // 省份名称
                                $('<span/>', {
                                    class: 'province_name',
                                    text: item.name
                                })
                            )
                            .append(function () {
                                // 城市数量
                                if (item.children) {
                                    return $('<span/>', {
                                        class: 'ratio'
                                    })
                                }
                            })
                    ).append(function () {
                        // 城市列表
                        if (item.children) {
                            return $('<div/>', {
                                class: 'citys'
                            }).append(
                                $('<i/>', {
                                    class: 'jt'
                                }).append($('<i/>', {}))
                            ).append(
                                _this.getSmallCitys(item.children)
                            )
                        }
                    })
            })
        )
    }

    /**
     * 遍历城市
     * @param datas
     * @returns {jQuery}
     * @constructor
     */
    public getSmallCitys(datas) {
        return $('<div/>', {
            class: 'row-div clearfloat'
        }).append(
            $.map(datas, function (item) {
                return $('<p/>', {}).append(
                    $('<label/>', {}).append(
                        $('<input/>', {
                            id: 'region_' + item.id,
                            type: 'checkbox',
                            name: 'city[]',
                            class: 'city',
                            change: function () {
                                let $citys = $(this).parents('.citys')
                                    , $placeTooltips = $(this).parents('.place-tooltips')
                                    , tf = $citys.find('input:checked').length
                                    , $province = $placeTooltips.find('.province')
                                    , $ratio = $placeTooltips.find('.ratio');
                                if (tf > 0) {
                                    $province.prop('checked', true);
                                    $ratio.html('(' + tf + '/' + $citys.find('input').length + ')');
                                } else if (tf === 0) {
                                    $province.prop('checked', false);
                                    $ratio.html('');
                                }
                            }
                        })
                    ).append(
                        $('<span/>', {
                            text: item.name
                        })
                    )
                )
            })
        )
    }

    /**
     * 获取已选中的省市id
     * @returns {array}
     * @constructor
     */
    public getCheckedIds () {
        let checkedIds = [];
        $('input[type=checkbox][name="city[]"]:checked').each(function (index, item) {
            checkedIds.push(item.id.replace('region_', ''));
        });
        return checkedIds;
    }

    /**
     * 获取已选中的省市id (树状)
     * @returns {Array}
     */
    public getCheckedTree() {
        let _this = this;
        // 遍历省份
        let data = [];
        $('input.province:checked').each(function (index, province) {
            let $this = $(this)
                , id = province.id.replace('region_', '')
                , $citys = $this.parent().next()
                , $cityInputChecked = $citys.find('input.city:checked')
                , cityData = []
                , cityTotal = Object.keys(_this.datas[id].children).length;
            // 遍历城市
            if (cityTotal !== $cityInputChecked.length) {
                $cityInputChecked.each(function (index, item) {
                    cityData.push({id: item.id.replace('region_', ''), name: $(this).next().text()});
                });
            }
            data.push({
                id: id,
                name: $this.next().text(),
                children: cityData
            });
        });
        return data;
    }

    /**
     * 获取已选中地区内容
     * @returns {{content: string, checkedIds: *|Array}}
     */
    public getCheckedContent() {
        // 获取已选中的省市id
        let dataTree = this.getCheckedTree()
            , checkedIds = this.getCheckedIds()
            , content = '';
        if (checkedIds.length === 373) {
            content = '全国';
        } else {
            let str = '';
            dataTree.forEach(function (item) {
                str += item.name;
                if (item.children.length > 0) {
                    let cityStr = '';
                    item.children.forEach(function (city) {
                        cityStr += city.name + '、';
                    });
                    str += ' (<span class="am-link-muted">'
                        + cityStr.substring(0, cityStr.length - 1) + '</span>)';
                }
                str += '、';
            });
            content = str.substring(0, str.length - 1);
        }
        return {
            content: content,
            ids: checkedIds
        };
    }

    /**
     * 批量选中
     * @param checkedIds 已选中的区域ID: 用于编辑
     * @constructor
     */
    public setChecked(checkedIds) {
        let $place = $('.place-div');
        $.each(checkedIds, function (i, id) {
            let item = $place.find('#region_' + id);
            if (item.length < 1) {
                return;
            }
            item[0].checked = true;
            item.trigger('change');
        });
    }

    /**
     * 批量删除已存在的区域
     * @param alreadyIds 已存在的区域ID: 用于新增
     * @constructor
     */
    public setAlready(alreadyIds) {
        let $place = $('.place-div');
        $.each(alreadyIds, function (i, id) {
            let $p = $place.find('#region_' + id).parent().parent()
                , $siblings = $p.siblings();
            $siblings.length > 0 ? $p.remove() : $p.closest('.place-tooltips').remove();
        });
    }

    /**
     * 清空
     */
    public destroy() {
        let $place = $('.place-div');
        $place.find('input[type=checkbox]').prop('checked', false);
        $place.find('.ratio').html('');
    }
}

class Delivery {
    /**
     *
     */
    constructor(
        public element: JQuery
    ) {
        let that = this;
        $.getJSON(BASE_URI + 'region/tree', function(data) {
            if (data.code == 200) {
                that.init(data.data);
            }
        });
    }

    public region: RegionalChoice;

    public dialog: any;

    public init(data) {
        this.dialog = $('.regional-choice').dialog();
        this.region = new RegionalChoice(this.dialog.find('.dialog-body'), data);
        this.initCreateRegion();
        this.clickEditEvent();
        this.clickDeleteEvent();
        this.clickMethodEvent();
    }

    /**
     * 初始化添加区域事件
     */
    public initCreateRegion() {
        let _this = this;
        _this.element.find('.add-region').click(function () {
            // 渲染地域
            let str = '';
            $(_this.element).find('input[type=hidden]').each(function (index, item) {
                str += $(item).val() + ',';
            });
            let alreadyIds = str.length > 0 ? str.substring(0, str.length - 1).split(',') : [];
            if (alreadyIds.length === 373) {
                Dialog.tip('已经选择了所有区域~');
                return false;
            }
            _this.region.render(alreadyIds);
            _this.showRegionalModal(function () {
                // 弹窗交互完成
                let Checked = _this.region.getCheckedContent();
                Checked.ids.length > 0 && _this.appendRulesTr(Checked.content, Checked.ids);
            });
        });
    }

    /**
     * 创建可配送区域规则
     */
    public appendRulesTr(regionStr, checkedIds) {
        let $html = $(
            '<tr>' +
            '<td class="am-text-left">' +
            '   <p class="selected-content am-margin-bottom-xs">' +
            '   ' + regionStr +
            '   </p>' +
            '   <p class="operation am-margin-bottom-xs">' +
            '       <a class="edit" href="javascript:;">编辑</a>' +
            '       <a class="delete" href="javascript:;">删除</a>' +
            '   </p>' +
            '   <input type="hidden" name="shipping[region][]" value="' + checkedIds + '">' +
            '</td>' +
            '<td>' +
            '   <input type="number" name="shipping[first_step][]" value="1" required>' +
            '</td>' +
            '<td>' +
            '   <input type="number" name="shipping[first_fee][]" value="0.00" required>' +
            '</td>' +
            '<td>' +
            '   <input type="number" name="shipping[additional][]" value="0">' +
            '</td>' +
            '<td>' +
            '   <input type="number" name="shipping[additional_fee][]" value="0.00">' +
            '</td>' +
            '<td>' +
            '   <input type="number" name="shipping[free_step][]" value="0.00">' +
            '</td>' +
            '</tr>'
        );
        this.element.children().find('tr:last').before($html);
    }

    /**
     * 显示区域选择窗口
     * @param callback
     */
    public showRegionalModal(callback) {
        let _this = this;
        this.dialog.on('done', function() {
            callback && callback();
            this.hide();
        }).on('close', function() {
            // 销毁已选中区域
            _this.region.destroy();
        }).css({
            width: '820px',
            height: '520px'
        }).showCenter();
    }

    /**
     * 编辑区域事件
     */
    public clickEditEvent () {
        let _this = this
            , $table = this.element;
        $table.on('click', '.edit', function () {
            // 渲染地域
            let $html = $(this).parent().parent()
                , $content = $html.find('.selected-content')
                , $input = $html.find('input[type=hidden]'),
                str = '';
            $(_this.element).find('input[type=hidden]').each(function (index, item) {
                if (item == $input[0]) {
                    return;
                }
                str += $(item).val() + ',';
            });
            _this.region.render(str.split(','), $input.val().split(','));
            // 显示地区选择弹窗
            _this.showRegionalModal(function () {
                // 弹窗交互完成
                let Checked = _this.region.getCheckedContent();
                if (Checked.ids.length > 0) {
                    $content.html(Checked.content);
                    $input.val(Checked.ids);
                }
            });
        });
    }

    /**
     * 删除区域事件
     */
    public clickDeleteEvent() {
        let $table = this.element;
        $table.on('click', '.delete', function () {
            let $delete = $(this);
            confirm('确定要删除吗？') && $delete.parents('tr').remove();;
        });
    }

    /**
     * 切换计费方式
     */
    public clickMethodEvent () {
        $('input:radio[name="method"]').change(function (e) {
            let $first = $('.first')
                , $additional = $('.additional');
            if (e.currentTarget.value === '1')
                $first.text('首重 (Kg)') && $additional.text('续重 (Kg)');
            else
                $first.text('首件 (个)') && $additional.text('续件 (个)');
        });
    }
}

function bindShipping(baseUri: string) {
    BASE_URI = baseUri;
    let shipping = new Delivery($('.regional-table'));
}

function bindEditAd() {
    let groups = $(".type-group .input-group");
    $("#type").change(function() {
        groups.eq(parseInt($(this).val()) % 2).show().siblings().hide();
    }).trigger('change');
}


$(function() {
    $(document).on('click', '[data-type=toggle]', function() {
        let that = $(this);
        postJson(that.data('url'), function(data) {
            if (data.code == 200) {
                that.toggleClass('active');
            }
        });
    });
});