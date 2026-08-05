var ignoredKeyCode = [
        9, 13, 17, 19, 20, 27, 33, 34, 35, 36, 37, 39, 44, 92, 113, 114, 115, 118,
        119, 120, 122, 123, 144, 145,
    ],
    allowOptions = [
        'source',
        'empty',
        'limit',
        'cache',
        'cacheExpires',
        'focusOpen',
        'enterSelect',
        'selectFirst',
        'changeWhenSelect',
        'highlightMatches',
        'ignoredKeyCode',
        'customLabel',
        'customValue',
        'onBeforeSend',
        'onBeforeShow',
        'onEmpty',
        'onItem',
        'onListOpen',
        'onListClose',
        'onBeforeLaunch',
        'template',
        'offset',
        'format',
        'combine',
        'callback',
        'minLength',
        'delay',
    ],
    userAgent = window.navigator.userAgent || window.navigator.vendor || (window as any).opera,
    isFirefox = /Firefox/i.test(userAgent),
    isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(userAgent),
    isFirefoxMobile = isFirefox && isMobile;









class AutoCompleter {

    private static _caches: any = {};
    private static _guid = 0;

    
    constructor(
        private element: JQuery<HTMLInputElement>,
        options?: IAutoCompleteOption
    ) {
        this.options = $.extend({}, new DefaultAutoCompleteOption(), options);
        this.init();
    }


    public options: IAutoCompleteOption;
    private $body = $(document.body);
    private target!: JQuery<HTMLDivElement>;
    private items?: JQuery<HTMLDivElement>;
    private $selected?: JQuery<HTMLDivElement>;
    private index = 0;
    private hintText = '';
    private guid = AutoCompleter._guid ++;
    private ajaxResponse?: any;
    private delayTimeout = 0;
    private jqxhr?: JQuery.jqXHR;
    private queryKeyword = '';
    private focused = false;

    public open() {
        this.options.onListOpen!(this.target, this.ajaxResponse);
        

        if (
            !this.element.prop('disabled') &&
            !this.target.hasClass('--with-open') &&
            this.items &&
            this.items.length
        ) {
            this.target
                .removeClass('--with-closed')
                .addClass('--with-open');

            this.$body.on('click.autocompleter-' + this.guid, this.closeHelper);
        }
    }
    public close() {

        this.options.onListClose!();

        if (this.target.hasClass('--with-open')) {
            this.target.removeClass('--with-open')
                .addClass('--with-closed');
            this.$body.off('.autocompleter-' + this.guid);
        }
    }

    public destroy() {
        if (!this.element.data('autocomplete')) {
            return;
        }
        // Abort xhr
        if (this.jqxhr) {
            this.jqxhr.abort();
        }

        // If has selected item & open - confirm it
        if (this.target.hasClass('open')) {
            this.target
            .find('.--autocomplete-item.--with-selected')
            .trigger('click.autocompleter');
        }

        // Remove autocompleter & unbind events
        this.element.off('.autocompleter').removeData('autocomplete');
        this.target.off('.autocompleter').remove();
    }

    private init() {
        if (this.element.data('autocomplete')) {
            return;
        }
        // Check for as local or .json file
        if (
            typeof this.options.source === 'string' &&
            (this.options.source.slice(-5) === '.json' || this.options.asLocal === true)
        ) {
            $.ajax({
                url: this.options.source,
                type: 'GET',
                dataType: 'json',
                async: false,
            }).done(res => {
                this.options.source = res;
            });
        }

        var html =
        '<div class="autocompleter ' + this.options.customClass!.join(' ') +'" id="autocompleter-' + this.guid + '">';

        if (this.options.hint) {
            html += '<div class="--autocomplete-hint"></div>';
        }

        html += '<ul class="--autocomplete-pane"></ul>';
        html += '</div>';

        this.element.data('autocomplete', 1).after(html);

        this.target = this.element.next('.--autocomplete-control').eq(0) as any;

        const that = this;

        // Bind autocompleter events
        this.target
            .on('mousedown.autocompleter', '.--autocomplete-item', null,  function(e: any) {
                that.select(e, $(this));
            })
            .on('mousedown.autocompleter', '.--autocomplete-pane', null, function () {
                return false;
            });

        // Bind node events
        this.element
            .on('keyup.autocompleter', null, this.onKeyup)
            .on('keydown.autocompleter', null, function(e) {
                that.onKeydown(e, $(this));
            })
            .on('focus.autocompleter', null, this.onFocus)
            .on('blur.autocompleter', null, this.onBlur)
            .on('mousedown.autocompleter', null, this.onMousedown);
    }

    private search(query, source) {
        const response: any[] = [];

        query = query.toUpperCase();

        if (source.length) {
            for (let i = 0; i < 2; i++) {
                for (let item in source) {
                    if (response.length < this.options.limit!) {
                        var label =
                            this.options.customLabel && source[item][this.options.customLabel as string]
                            ? source[item][this.options.customLabel as string]
                            : source[item].label;

                        switch (i) {
                            case 0:
                            if (label.toUpperCase().search(query) === 0) {
                                response.push(source[item]);
                                delete source[item];
                            }
                            break;

                            case 1:
                            if (label.toUpperCase().search(query) !== -1) {
                                response.push(source[item]);
                                delete source[item];
                            }
                            break;
                        }
                    }
                }
            }
        }

        return response;
    }

    private launch() {
        // Clear previous timeout
        clearTimeout(this.delayTimeout);

        this.options.onBeforeLaunch!(this.target, this.element);
        this.queryKeyword = this.element.val()?.trim() ?? '';

        if (
            (!this.options.empty && this.queryKeyword.length === 0) ||
            (this.options.minLength && this.queryKeyword.length < this.options.minLength)
        ) {
            this.clear();
            return;
        }

        if (this.options.delay) {
            // Be careful: delay used also with local source
            this.delayTimeout = setTimeout(() => {
                this.xhr();
            }, this.options.delay);
        } else {
            this.xhr();
        }
    }

    private xhr() {
        if (typeof this.options.source === 'object') {
            this.clear();

            // Local search
            const search = this.search(this.queryKeyword, this.clone(this.options.source));

            if (search.length) {
                this.response(search);
            }
        } else {
            if (this.jqxhr) {
                this.jqxhr.abort();
            }

            let ajaxData = {
                limit: this.options.limit,
                query: this.queryKeyword,
            };

            if (typeof this.options.combine === 'function') {
                ajaxData = this.options.combine(ajaxData);
            }
            const url = this.options.source;
            this.jqxhr = $.ajax({
                url: this.options.source,
                dataType: 'json',
                crossDomain: true,
                data: ajaxData,
                beforeSend: xhr => {
                    this.target.addClass('autocompleter-ajax');
                    this.clear();

                    this.options.onBeforeSend!();

                    if (this.options.cache) {
                        const stored = AutoCompleter.getCache(url, this.options.cacheExpires);

                        if (stored) {
                            xhr.abort();
                            this.response(stored);
                        }
                    }
                },
            })
            .done(response => {
                // Get subobject from responce
                if (this.options.offset) {
                    response = this.grab(response, this.options.offset as any);
                }

                if (typeof this.options.format === 'function') {
                    response = this.options.format(response) || [];
                }

                // Set cache
                if (this.options.cache) {
                    AutoCompleter.setCache(url, response);
                }

                this.response(response);
            })
            .always(() => {
                this.target.removeClass('--with-ajax');
            });
        }
    }

    private clear() {
        // Clear data
        this.ajaxResponse = undefined;
        this.items = undefined;
        this.$selected = undefined;
        this.index = 0;
        this.target.find('.--autocomplete-pane').empty();
        this.target
            .find('.--autocomplete-hint')
            .removeClass('--with-hint-open')
            .empty();
        this.hintText = '';

        this.close();
    }

    private response(response: any[]) {
        this.buildList(response);

        if (this.target.hasClass('--with-focus')) {
            this.open();
        }
    }

    private buildList(list: any[]) {
        let menu = '';

        for (let item = 0, count = list.length; item < count; item++) {
            const classes = ['--autocomplete-item'],
            highlightReg = new RegExp(this.queryKeyword, 'gi');

            if (this.options.selectFirst && item === 0 && !this.options.changeWhenSelect) {
                classes.push('--with-selected');
            }

            let label =
                this.options.customLabel && list[item][this.options.customLabel as string]
                ? list[item][this.options.customLabel as string]
                : list[item].label,
            clear = label;

            label = this.options.highlightMatches
            ? label.replace(highlightReg, '<strong>$&</strong>')
            : label;

            const value =
            this.options.customValue && list[item][this.options.customValue as string]
                ? list[item][this.options.customValue as string]
                : list[item].value;

            // Apply custom template
            if (this.options.template) {
                var template = (this.options.template as string).replace(/({{ label }})/gi, label);

                for (var property in list[item]) {
                    if (Object.prototype.hasOwnProperty.call(list[item], property)) {
                    var regex = new RegExp('{{ ' + property + ' }}', 'gi');

                    template = template.replace(regex, list[item][property]);
                    }
                }

                label = template;
            }

            if (value) {
                menu +=
                    '<li data-value="' +
                    value +
                    '" data-label="' +
                    clear +
                    '" class="' +
                    classes.join(' ') +
                    '">' +
                    label +
                    '</li>';
            } else {
                menu +=
                    '<li data-label="' +
                    clear +
                    '" class="' +
                    classes.join(' ') +
                    '">' +
                    label +
                    '</li>';
            }
        }

        // Set hint
        if (list.length && this.options.hint) {
            const hintLabel =
                this.options.customLabel && list[0][this.options.customLabel as string]
                ? list[0][this.options.customLabel as string]
                : list[0].label,
            hint =
                hintLabel.substr(0, this.queryKeyword.length).toUpperCase() ===
                this.queryKeyword.toUpperCase()
                ? hintLabel
                : false;

            if (hint && this.queryKeyword !== hintLabel) {
                const hintReg = new RegExp(this.queryKeyword, 'i');
                const hintText = hint.replace(hintReg, '<span>' + this.queryKeyword + '</span>');

                this.target
                    .find('.--autocomplete-hint')
                    .addClass('--with-hint-open')
                    .html(hintText);
                this.hintText = hintText;
            }
        }

        // Update data
        this.ajaxResponse = list;
        this.target.find('.--autocomplete-pane').html(menu);
        this.$selected = this.target.find('.--with-selected')
            .length
            ? this.target.find('.--with-selected')
            : undefined;
        this.items = list.length
            ? this.target.find('.--autocomplete-item')
            : undefined;
        this.index = this.$selected ? this.items!.index(this.$selected) : -1;
        this.target.find('.--autocomplete-item').each((i, j) => {
            $(j).data(this.ajaxResponse[i]);
            this.options.onItem!(this.target, $(j), i, this.ajaxResponse[i]);
        });

        if (!list.length) {
            this.options.onEmpty!();
        }

        this.options.onBeforeShow!();
    }

    private select(e: any, target: JQuery<HTMLDivElement>) {
        // Disable middle & right mouse click
        if (e.type === 'mousedown' && $.inArray(e.which, [2, 3]) !== -1) {
            return;
        }


        e.preventDefault();
        e.stopPropagation();

        if (e.type === 'mousedown' && target.length) {
            this.$selected = target;
            this.index = target.index();
        }

        if (!this.element.prop('disabled')) {
            this.close();
            this.update();

            if (e.type === 'click') {
                this.element.trigger('focus', [true]);
            }
        }
    }

    private onKeyup(e: any) {
        let code = e.keyCode ? e.keyCode : e.which;

        if (
            (code === 40 || code === 38) &&
            this.target.hasClass('--with-open')
        ) {
            // Arrows up & down
            var len = this.items!.length,
            next,
            prev;

            if (len) {
                // Determine new index
                if (len > 1) {
                    if (this.index === len - 1) {
                        next = this.options.changeWhenSelect ? -1 : 0;
                        prev = this.index - 1;
                    } else if (this.index === 0) {
                        next = this.index + 1;
                        prev = this.options.changeWhenSelect ? -1 : len - 1;
                    } else if (this.index === -1) {
                        next = 0;
                        prev = len - 1;
                    } else {
                        next = this.index + 1;
                        prev = this.index - 1;
                    }
                } else if (this.index === -1) {
                    next = 0;
                    prev = 0;
                } else {
                    prev = -1;
                    next = -1;
                }

                this.index = code === 40 ? next : prev;

                // Update HTML
                this.items!.removeClass('--with-selected');

                if (this.index !== -1) {
                    this.items!.eq(this.index).addClass('--with-selected');
                }

                this.$selected = this.target.find('.--with-selected')
                    .length
                    ? this.target.find('.--with-selected')
                    : undefined;

                if (this.options.changeWhenSelect) {
                    this.setValue();
                }
            }
        } else if (
            $.inArray(code, ignoredKeyCode) === -1 &&
            $.inArray(code, this.options.ignoredKeyCode ?? []) === -1
        ) {
            // Typing
            this.launch();
        }
    }

    private onKeydown(e: any, target: JQuery<HTMLDivElement>) {
        var code = e.keyCode ? e.keyCode : e.which;

        if (code === 40 || code === 38) {
            e.preventDefault();
            e.stopPropagation();
        } else if (code === 39) {
            // Right arrow
            if (
                this.options.hint &&
                this.hintText &&
                this.target
                .find('.--autocomplete-hint')
                .hasClass('--with-hint-open')
            ) {
                e.preventDefault();
                e.stopPropagation();

                var hintOrigin = this.target.find('.--autocomplete-item').length
                    ? this.target
                        .find('.--autocomplete-item')
                        .eq(0)
                        .attr('data-label')
                    : false;

                if (hintOrigin) {
                    this.queryKeyword = hintOrigin;
                    this.setHint();
                }
            }
        } else if (code === 13) {
            // Enter
            if (this.options.enterSelect) {
                if (
                    this.target.hasClass('--with-open') &&
                    this.$selected
                ) {
                    this.select(e, target);
                }
            } else {
                e.preventDefault();
                e.stopPropagation();
            }
        }
    }

    private onFocus(e: any, internal: boolean) {
        if (!internal) {
            this.target.addClass('--with-focus');

            if (
                !this.element.prop('disabled') &&
                !this.target.hasClass('--with-open')
            ) {
                if (this.options.focusOpen) {
                    this.launch();
                    this.focused = true;

                    setTimeout(() => {
                        this.focused = false;
                    }, 500);
                }
            }
        }
    }

    private onBlur(e: any, internal: boolean) {
        e.preventDefault();
        e.stopPropagation();

        if (!internal) {
            this.target.removeClass('--with-focus');
            this.close();
        }
    }

    private onMousedown(e: any) {
        // Disable middle & right mouse click
        if (e.type === 'mousedown' && $.inArray(e.which, [2, 3]) !== -1) {
            return;
        }

        const data = e.data;

        if (data.$list && !data.focused && !this.element.is(':disabled')) {
            if (isMobile && !isFirefoxMobile) {
                const el = data.$select[0];

                if (window.document.createEvent) {
                    // All
                    var evt = window.document.createEvent('MouseEvents');
                    evt.initMouseEvent(
                        'mousedown',
                        false,
                        true,
                        window,
                        0,
                        0,
                        0,
                        0,
                        0,
                        false,
                        false,
                        false,
                        false,
                        0,
                        null
                    );
                    el.dispatchEvent(evt);
                } else if (el.fireEvent) {
                    // IE
                    el.fireEvent('onmousedown');
                }
            } else {
                // Delegate intent
                if (this.target.hasClass('--with-closed')) {
                    this.open();
                } else if (this.target.hasClass('--with-open')) {
                    this.close();
                }
            }
        }
    }




    private closeHelper(e: any) {
        if (
            $(e.target).hasClass('autocompleter-node') ||
            $(e.target).parents('.autocompleter').length
        ) {
            return false;
        }

        this.close();
    }


    private setHint() {
        this.setValue();
        this.handleChange();
        this.launch();
    }

    private setValue() {
        if (this.$selected) {
            if (
                this.hintText &&
                this.target
                .find('.--autocomplete-hint')
                .hasClass('--with-hint-open')
            ) {
                this.target
                .find('.--autocomplete-hint')
                .removeClass('--with-hint-open');
            }

            this.element.val(
                this.$selected.attr('data-value')
                ? this.$selected.attr('data-value')
                : this.$selected.attr('data-label') as any
            );
        } else {
            if (
                this.hintText &&
                !this.target
                .find('.--autocomplete-hint')
                .hasClass('--with-hint-open')
            ) {
                this.target
                .find('.--autocomplete-hint')
                .addClass('--with-hint-open');
            }

            this.element.val(this.queryKeyword);
        }
    }

    private update() {
        this.setValue();
        this.handleChange();
        this.clear();
    }

    private handleChange() {
        this,this.options.callback?.call(
            this.target,
            this.element.val(),
            this.index,
            this,this.ajaxResponse[this.index]
        );
        this.element.trigger('change');
    }

    private grab(response: any, offset: string) {
        const items: any[] = offset.split('.');

        while (response && items.length) {
            response = response[items.shift()];
        }

        return response;
    }

    private clone(obj: any) {
        let copy: any;

        if (null === obj || 'object' !== typeof obj) {
            return obj;
        }

        copy = obj.constructor();

        for (var attr in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, attr)) {
                copy[attr] = obj[attr];
            }
        }

        return copy;
    }

    private static getCache(url: string, expires: false|number = false): any {
        if (!url) {
            return false;
        }
        let response: any = false;
        const item = this._caches[url];

        if (item && item.value) {
            response = item.value;

            if (
                item.timestamp &&
                expires &&
                +new Date() - item.timestamp > expires * 1000
            ) {
                return false;
            } else {
                return response;
            }
        } else {
            return false;
        }
    }

    private static setCache(url: string, data: any) {
        if (url && data) {
            this._caches[url] = {
                value: data,
                timestamp: +new Date(),
            };
        }
    }

    private static clearCache() {
        this._caches = {};
    }
}

interface IAutoCompleteOption {
    source?: string|any;
    asLocal?: boolean;
    empty?: boolean;
    limit?: number;
    minLength?: number;
    delay?: number;
    customClass?: string[];
    cache?: boolean;
    cacheExpires?: number;
    focusOpen?: boolean;
    enterSelect?: boolean;
    hint?: boolean;
    selectFirst?: boolean;
    changeWhenSelect?: boolean;
    highlightMatches?: boolean;
    ignoredKeyCode?: number[];
    customLabel?: boolean| string;
    customValue?: boolean|string;
    onBeforeSend?: Function;
    onBeforeShow?: Function;
    onEmpty?: Function;
    onItem?: Function;
    onListOpen?: Function;
    onListClose?: Function;
    onBeforeLaunch?: Function;
    template?: string|boolean;
    offset?: string|boolean;
    format?: Function;
    combine?: Function;
    callback?: Function;
}

class DefaultAutoCompleteOption implements IAutoCompleteOption {
    asLocal = false;
    empty = true;
    limit = 10;
    minLength = 0;
    delay = 0;
    customClass = [];
    cache = true;
    cacheExpires = 86400;
    focusOpen = true;
    enterSelect = true;
    hint = false;
    selectFirst = false;
    changeWhenSelect = true;
    highlightMatches = false;
    ignoredKeyCode = [];
    customLabel = false;
    customValue = false;
    onBeforeSend = $.noop;
    onBeforeShow = $.noop;
    onEmpty = $.noop;
    onItem = $.noop;
    onListOpen = $.noop;
    onListClose = $.noop;
    onBeforeLaunch = $.noop;
    template = false;
    offset = false;
    callback = $.noop;
}

;
(function ($: any) {
    $.fn.autocomplete = function (options: IAutoCompleteOption) {
        if (this.length > 1) {
            this.each(function(this: HTMLInputElement) {
                new AutoCompleter($(this), options);
            });
            return;
        }
        return new AutoCompleter(this, options);
    };
})(jQuery);