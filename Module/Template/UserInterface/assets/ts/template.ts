function bindPage(pageId: number) {
    const editor = new VisualEditor($('#page-box'));
    const postCallback = (data: any, success: Function, failure?: Function) => {
        if (data.code == 200) {
            success && success(data.data);
            return;
        }
        if (failure) {
            failure(data.message);
        }
        Dialog.tip(data.message);
    };
    editor.on(EditorEventGetWeights, function(success, failure) {
        $.getJSON(BASE_URI + 'weight?id=' + pageId, data => {
            postCallback(data, success, failure);
        });
    }).on(EditorEventGetPage, function(success, failure) {
        $.getJSON(BASE_URI + 'page/detail?id=' + pageId, data => {
            postCallback(data, success, failure);
        });
    }).on(EditorEventPageList, function(success, failure) {
        $.getJSON(BASE_URI + 'page/search?id=' + pageId, data => {
            postCallback(data, success, failure);
        });
    }).on(EditorEventPageSetting, function(success, failure) {
        $.getJSON(BASE_URI + 'page/setting?id=' + pageId, data => {
            postCallback(data, success, failure);
        });
    }).on(EditorEventSavePageSetting, function(data: any, success, failure) {
        postJson(BASE_URI + 'page/save_setting', typeof data !== 'object' ? data : {
            id: pageId,
            ...data
        }, res => {
            postCallback(res, success, failure);
        });
    }).on(EditorEventWeightTree, function(success, failure) {
        $.getJSON(BASE_URI + 'weight/search?id=' + pageId, data => {
            postCallback(data, success, failure);
        });
    }).on(EditorEventGetWeightProperty, function(weightId: number, success, failure) {
        $.getJSON(BASE_URI + 'weight/setting?id=' + weightId, data => {
            postCallback(data, success, failure);
        });
    }).on(EditorEventWeightForm, function(weightId: number, success, failure) {
        $.getJSON(BASE_URI + 'weight/form?id=' + weightId, data => {
            postCallback(data, success, failure);
        });
    }).on(EditorEventSaveWeightProperty, function(weightId: number, data: any, success, failure) {
        postJson(BASE_URI + 'weight/save', typeof data !== 'object' ? data : {
            id: weightId,
            ...data
        }, res => {
            postCallback(res, success, failure);
        });
    }).on(EditorEventRefreshWeight, function(weightId: number, success, failure) {
        $.getJSON(BASE_URI + 'weight/refresh?id=' + weightId, data => {
            postCallback(data, success, failure);
        });
    }).on(EditorEventAddWeight, function(data: any, success, failure) {
        postJson(BASE_URI + 'weight/create', {
            page_id: pageId,
            ...data
        }, res => {
            postCallback(res, success, failure);
        });
    }).on(EditorEventMoveWeight, function(data: any, success, failure) {
        postJson(BASE_URI + 'weight/move', {
            page_id: pageId,
            ...data
        }, res => {
            postCallback(res, success, failure);
        });
    }).on(EditorEventRemoveWeight, function(weightId: number, success, failure) {
        postJson(BASE_URI + 'weight/destroy', {
            id: weightId
        }, res => {
            postCallback(res, success, failure);
        });
    }).on(EditorEventSavePage, function(weights: any[], success, failure) {
        postJson(BASE_URI + 'weight/batch_save', {
            id: pageId,
            weights
        }, res => {
            postCallback(res, data => {
                success && success(data);
                Dialog.tip('保存成功！');
            }, failure);
        });
    });
    editor.run();
    $('body').addClass('full-edit-mode');
    $(".mobile-size li").on('click',function() {
        const $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        $this.closest(".mobile-size").parent().removeClass("open");
        let size = $this.attr("data-size").split("*");
        if (size.length < 2 || !size[0] || !size[1]) {
            editor.normal();
            return;
        }
        editor.mobile(parseInt(size[0]), parseInt(size[1]));
    });
    $(".navbar>li>div").on('click',function() {
        $(this).parent().toggleClass("open");
    });

    $(".mobile-rotate").on('click',function() {
        editor.rotate();
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

    public on(event: string, tag: string | Function, cb?: (event: JQuery.Event) => void) {
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
    $('*[data-type="add"]').on('click',function() {
        box.show();
    });
    box.on('done', (selected: number[]) => {
        
    });
}