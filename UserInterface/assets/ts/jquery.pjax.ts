interface PjaxOption {
    timeout?: number,
    push?: boolean,
    replace?: boolean,
    type?: string,
    dataType?: string,
    scrollTo?: number|boolean,
    maxCacheLength?: number,
    url?: string | (() => string);
    requestUrl?: string;
    container?: string;
    target?: any;
    data?: FormData|any;
    processData?: boolean;
    contentType?: false|string;
    fragment?: string;
    id?: number;
    context?: JQuery<Element>;
}

class PjaxDefaultOption implements PjaxOption {
    timeout = 650;
    push = true;
    replace = false;
    type = 'GET';
    dataType = 'html';
    scrollTo = 0;
    maxCacheLength = 20;
}

class Pjax {

    public static instance: Pjax = new Pjax();

    public static bind<T>(element: JQuery<T>, selector: string|PjaxOption, container?: string|PjaxOption, options?: PjaxOption): JQuery<T> {
        if (typeof selector === 'object') {
            options = selector;
            selector = undefined;
        }
        if (typeof container === 'string') {
            options = this.optionsFor(container, options);
        }
        return element.on('click.pjax', selector, function(event) {
            const $this = $(this);
            if ($this.attr('target') === '_blank') {
                return;
            }
            let opts = options;
            if (!opts.container) {
                opts = $.extend({}, options)
                opts.container = $this.attr('data-pjax')
            }
            Pjax.instance.handleClick(event, opts)
        });
    }

    public static load(element: JQuery, options?: PjaxOption) {
        options.context = element;
        Pjax.instance.get(options);
    }

    constructor(
        option?: PjaxOption
    ) {
        this.options = $.extend({}, new PjaxDefaultOption(), option);
        if (this.initialState && this.initialState.container) {
            this.state = this.initialState;
        }
        this.bindEvent();
    }

    private options: PjaxOption;
    private timeoutTimer: number;
    private initialPop = true;
    private initialURL = window.location.href;
    private initialState = window.history.state;
    private cacheMapping: any = {};
    private cacheForwardStack = [];
    private cacheBackStack = [];

    private state: any;
    private xhr: JQuery.jqXHR;

    private bindEvent() {
        $(window).on('popstate.pjax', this.onPjaxPopstate.bind(this));
    }

    public handleClick(event: JQuery.ClickEvent, container: PjaxOption|JQuery, options?: PjaxOption) {
        options = Pjax.optionsFor(container, options);
    
        const link = event.currentTarget;
        
        const $link = $(link);
    
        if (link.tagName?.toUpperCase() !== 'A')
        {
            throw '$.fn.pjax or $.pjax.click requires an anchor element';
        }
        if ( event.which > 1 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey )
        {
            return;
        }
        if ( location.protocol !== link.protocol || location.hostname !== link.hostname )
        {
            return;
        }
    
        if ( link.href.indexOf('#') > -1 && this.stripHash(link) == this.stripHash(location as any) )
        {
            return;
        }
        if (event.isDefaultPrevented())
        {
            return;
        }
    
        const defaults: PjaxOption = {
            url: link.href,
            container: $link.attr('data-pjax'),
            target: link
        };
    
        const opts = $.extend({}, defaults, options);
        const clickEvent = $.Event('pjax:click');
        $link.trigger(clickEvent, [opts]);
    
        if (!clickEvent.isDefaultPrevented()) {
            this.get(opts);
            event.preventDefault();
            $link.trigger('pjax:clicked', [opts]);
        }
    }

    public handleSubmit(event: JQuery.ClickEvent, container: PjaxOption|JQuery, options?: PjaxOption) {
        options = Pjax.optionsFor(container, options);
    
        const form = event.currentTarget;
        const $form = $(form);
    
        if (form.tagName?.toUpperCase() !== 'FORM')
        {
            throw '$.pjax.submit requires a form element';
        }
    
        const defaults: PjaxOption = {
            type: ($form.attr('method') || 'GET').toUpperCase(),
            url: $form.attr('action'),
            container: $form.attr('data-pjax'),
            target: form
        };
    
        if (defaults.type !== 'GET' && window.FormData !== undefined) {
            defaults.data = new FormData(form);
            defaults.processData = false;
            defaults.contentType = false;
        } else {
            if ($form.find(':file').length) {
                return;
            }
            defaults.data = $form.serializeArray();
        }
    
        this.get($.extend({}, defaults, options));
    
        event.preventDefault();
    }




    public reload(container: PjaxOption|JQuery, options?: PjaxOption) {
        const defaults = {
            url: window.location.href,
            push: false,
            replace: true,
            scrollTo: false
        };
    
        return this.get($.extend(defaults, Pjax.optionsFor(container, options)));
    }

    public get(options: PjaxOption) {
        options = $.extend(true, {}, this.options, options);
        if (typeof options.url === 'function') {
            options.url = options.url();
        }

        const hash = this.parseURL(options.url).hash;
        
        if (!options.context) {
            const containerType = typeof options.container;
            if (containerType !== 'string') {
                throw 'expected string value for "container" option; got ' + containerType;
            }
            options.context = $(options.container as any);
        }
        const context = options.context;
        if (!context.length) {
            throw 'the container selector "' + options.container + '" did not match anything';
        }
        if (!options.data) {
            options.data = {};
        }
        if (options.data instanceof Array) {
            options.data.push({name: '_pjax', value: options.container});
        } else {
            options.data._pjax = options.container;
        }

        const fire = (type: string, args: any[], props?: any) => {
            if (!props) {
                props = {};
            }
            props.relatedTarget = this.options.target;
            const event = $.Event(type, props);
            context.trigger(event, args);
            return !event.isDefaultPrevented();
        };

        if (!this.state) {
            this.state = {
                id: this.uniqueId(),
                url: window.location.href,
                title: document.title,
                container: options.container,
                fragment: options.fragment,
                timeout: options.timeout
            };
            window.history.replaceState(this.state, document.title);
        }
        this.abortXHR(this.xhr);

        const xhr = this.xhr = $.ajax({
            method: options.type,
            url: options.url,
            data: options.data,
            timeout: options.timeout,
            processData: options.processData,
            dataType: options.dataType,
            contentType: options.contentType,
            beforeSend: (xhr, settings) => {
                if (settings.type !== 'GET') {
                    settings.timeout = 0;
                }
            
                xhr.setRequestHeader('X-PJAX', 'true');
                xhr.setRequestHeader('X-PJAX-Container', options.container);
            
                if (!fire('pjax:beforeSend', [xhr, settings]))
                {
                    return false
                }
            
                if (settings.timeout > 0) {
                    this.timeoutTimer = setTimeout(function() {
                        if (fire('pjax:timeout', [xhr, options]))
                        {
                            xhr.abort('timeout');
                        }
                    }, settings.timeout);
            
                    settings.timeout = 0;
                }
            
                const url = this.parseURL(settings.url);
                if (hash) {
                    url.hash = hash;
                }
                options.requestUrl = this.stripInternalParams(url as any);
            },
            complete: (xhr, textStatus) => {
                if (this.timeoutTimer)
                {
                    clearTimeout(this.timeoutTimer);
                }
            
                fire('pjax:complete', [xhr, textStatus, options]);
            
                fire('pjax:end', [xhr, options]);
            },
            error: (xhr, textStatus, errorThrown) => {
                const container = this.extractContainer('', xhr, options);
  
                const allowed = fire('pjax:error', [xhr, textStatus, errorThrown, options]);
                if (options.type == 'GET' && textStatus !== 'abort' && allowed) {
                    this.locationReplace(container.url);
                }
            },
            success: (data, status, xhr) => {
                const previousState = this.state;
  
                const currentVersion = this.findVersion();
            
                const latestVersion = xhr.getResponseHeader('X-PJAX-Version');
                const container = this.extractContainer(data, xhr, options);
            
                const url = this.parseURL(container.url);
                if (hash) {
                    url.hash = hash;
                    container.url = url.href;
                }
                if (currentVersion && latestVersion && currentVersion !== latestVersion) {
                    this.locationReplace(container.url);
                    return;
                }
                if (!container.contents) {
                    this.locationReplace(container.url);
                    return;
                }
            
                this.state = {
                    id: options.id || this.uniqueId(),
                    url: container.url,
                    title: container.title,
                    container: options.container,
                    fragment: options.fragment,
                    timeout: options.timeout
                };
            
                if (options.push || options.replace) {
                    window.history.replaceState(this.state, container.title, container.url);
                }
            
                const blurFocus = $.contains(context as any, document.activeElement);

                if (blurFocus) {
                    try {
                        (document.activeElement as HTMLInputElement).blur();
                    } catch (e) {}
                }
            
                if (container.title) {
                    document.title = container.title;
                }
            
                fire('pjax:beforeReplace', [container.contents, options], {
                    state: this.state,
                    previousState: previousState
                });
                context.html(container.contents);
            
                const autofocusEl = context.find('input[autofocus], textarea[autofocus]').last()[0];
                if (autofocusEl && document.activeElement !== autofocusEl) {
                    autofocusEl.focus();
                }
            
                this.executeScriptTags(container.scripts);
            
                let scrollTo = options.scrollTo;
            
                if (hash) {
                    const name = decodeURIComponent(hash.slice(1));
                    const target = document.getElementById(name) || document.getElementsByName(name)[0];
                    if (target) {
                        scrollTo = $(target).offset().top;
                    }
                }
            
                if (typeof scrollTo == 'number') {
                    $(window).scrollTop(scrollTo);
                }
            
                fire('pjax:success', [data, status, xhr, options]);
            }
        });


        if (xhr.readyState > 0) {
            if (options.push && !options.replace) {
                this.cachePush(this.state.id, [options.container, this.cloneContents(context)]);
        
                window.history.pushState(null, '', options.requestUrl);
            }
        
            fire('pjax:start', [xhr, options]);
            fire('pjax:send', [xhr, options]);
        }
        return xhr;
    }

    private locationReplace(url: string) {
        window.history.replaceState(null, '', this.state.url);
        window.location.replace(url);
    }

    private onPjaxPopstate(event: any) {
        if (!this.initialPop) {
            this.abortXHR(this.xhr);
        }
    
        const previousState = this.state;
        const state = event.state;
        let direction: string;
    
        if (state && state.container) {
            if (this.initialPop && this.initialURL == state.url) {
                return;
            }
        
            if (previousState) {
                if (previousState.id === state.id) {
                    return;
                }
        
                direction = previousState.id < state.id ? 'forward' : 'back';
            }
        
            const cache = this.cacheMapping[state.id] || [];
            const containerSelector = cache[0] || state.container;
            const container = $(containerSelector), contents = cache[1];
        
            if (container.length) {
                if (previousState) {
                    this.cachePop(direction, previousState.id, [containerSelector, this.cloneContents(container)]);
                }
        
                const popstateEvent = $.Event('pjax:popstate', {
                    state: state,
                    direction: direction
                });
                container.trigger(popstateEvent);
        
                const options: PjaxOption = {
                    id: state.id,
                    url: state.url,
                    container: containerSelector,
                    push: false,
                    fragment: state.fragment,
                    timeout: state.timeout,
                    scrollTo: false
                };
        
                if (contents) {
                    container.trigger('pjax:start', [null, options]);
            
                    this.state = state;
                    if (state.title) {
                         document.title = state.title;
                    }
                    const beforeReplaceEvent = $.Event('pjax:beforeReplace', {
                        state: state,
                        previousState: previousState
                    });
                    container.trigger(beforeReplaceEvent, [contents, options]);
                    container.html(contents);
            
                    container.trigger('pjax:end', [null, options]);
                } else {
                    this.get(options);
                }
                container[0].offsetHeight;
            } else {
                this.locationReplace(location.href);
            }
        }
        this.initialPop = false;
    }

    public fallback(options: PjaxOption) 
    {
        const url = typeof options.url === 'function' ? options.url() : options.url;
        const method = options.type ? options.type.toUpperCase() : 'GET';
    
        const form = $('<form>', {
            method: method === 'GET' ? 'GET' : 'POST',
            action: url,
            style: 'display:none'
        });
    
        if (method !== 'GET' && method !== 'POST') {
            form.append($('<input>', {
                type: 'hidden',
                name: '_method',
                value: method.toLowerCase()
            }));
        }
    
        const data = options.data
        if (typeof data === 'string') {
            $.each(data.split('&'), (_, value) => {
                const pair = value.split('=');
                form.append($('<input>', {type: 'hidden', name: pair[0], value: pair[1]}));
            });
        } else if (data instanceof Array) {
            $.each(data, (_, value) => {
                form.append($('<input>', {type: 'hidden', name: value.name, value: value.value}));
            });
        } else if (typeof data === 'object') {
            for (const key in data)
            {
                form.append($('<input>', {type: 'hidden', name: key, value: data[key]}));
            }
        }
    
        $(document.body).append(form);
        form.trigger('submit');
    }

    private abortXHR(xhr: JQuery.jqXHR) {
        if (xhr && xhr.readyState < 4) {
            (xhr as any).onreadystatechange = $.noop;
            xhr.abort();
        }
    }

    private uniqueId() 
    {
        return (new Date).getTime();
    }

    private cloneContents(container: JQuery<Element>) {
        const cloned = container.clone();
        cloned.find('script').each(function(){
            if (!this.src) {
                $.data(this, 'globalEval', false);
            }
        });
        return cloned.contents();
    }

    private stripInternalParams(url: URL) 
    {
        url.search = url.search.replace(/([?&])(_pjax|_)=[^&]*/g, '').replace(/^&/, '');
        return url.href.replace(/\?($|#)/, '$1');
    }

    private parseURL(url: string) 
    {
        const a = document.createElement('a');
        a.href = url;
        return a;
    }

    private stripHash(location: URL) 
    {
        return location.href.replace(/#.*/, '');
    }

    private static optionsFor(container: string|PjaxOption, options: PjaxOption): PjaxOption
    {
        if (container && options) {
            options = $.extend({}, options);
            options.container = container as string;;
            return options;
        } else if ($.isPlainObject(container)) {
            return container as any;
        } else {
            return {container: container as string};
        }
    }

    private findAll<T>(elems: JQuery<T>, selector: string): JQuery<T>
    {
        return elems.filter(selector).add(elems.find(selector));
    }
    
    private parseHTML(html: string) 
    {
        return $.parseHTML(html, document, true);
    }

    private extractContainer(data: string, xhr: JQuery.jqXHR, options: PjaxOption) 
    {
        const obj = {} as any;
        const fullDocument = /<html/i.test(data);
        const serverUrl = xhr.getResponseHeader('X-PJAX-URL');
        obj.url = serverUrl ? this.stripInternalParams(
            this.parseURL(serverUrl) as any) : options.requestUrl;
    
        let $head: JQuery<Node[]>;
        let $body: JQuery<Node[]>;
        if (fullDocument) {
            $body = $(this.parseHTML(data.match(/<body[^>]*>([\s\S.]*)<\/body>/i)[0]));
            const head = data.match(/<head[^>]*>([\s\S.]*)<\/head>/i);
            $head = head != null ? $(this.parseHTML(head[0])) : $body;
        } else {
            $head = $body = $(this.parseHTML(data));
        }

        if ($body.length === 0)
        {
            return obj;
        }
    
        obj.title = this.findAll($head, 'title').last().text();
    
        if (options.fragment) {
            let $fragment = $body;
            if (options.fragment !== 'body') {
                $fragment = this.findAll($fragment, options.fragment).first();
            }
        
            if ($fragment.length) {
                obj.contents = options.fragment === 'body' ? $fragment : $fragment.contents();
        
                if (!obj.title)
                {
                    obj.title = $fragment.attr('title') || $fragment.data('title');
                }
            }
    
        } else if (!fullDocument) {
            obj.contents = $body;
        }
        if (obj.contents) {
            obj.contents = obj.contents.not(function() { return $(this).is('title') });
            obj.contents.find('title').remove();
            obj.scripts = this.findAll(obj.contents, 'script[src]').remove();
            obj.contents = obj.contents.not(obj.scripts);
        }
        if (obj.title) {
            obj.title = (obj.title as string).trim();
        }
    
        return obj
    }

    private executeScriptTags(scripts: JQuery<HTMLScriptElement>) 
    {
        if (!scripts) {
            return;
        }
    
        const existingScripts: JQuery<HTMLScriptElement> = $('script[src]');
    
        scripts.each(function() {
            const src = this.src;
            const matchedScripts = existingScripts.filter(function() {
                return this.src === src;
            })
            if (matchedScripts.length) {
                return;
            }
        
            const script = document.createElement('script');
            const type = $(this).attr('type');
            if (type) {
                script.type = type;
            }
            script.src = $(this).attr('src');
            document.head.appendChild(script);
        });
    }

    private cachePush(id: any, value: any) 
    {
        this.cacheMapping[id] = value;
        this.cacheBackStack.push(id);
  
        this.trimCacheStack(this.cacheForwardStack, 0);
  
        this.trimCacheStack(this.cacheBackStack, this.options.maxCacheLength);
    }

    private cachePop(direction: string, id: any, value: any) 
    {
        let pushStack: any[];
        let popStack: any[];
        this.cacheMapping[id] = value;
    
        if (direction === 'forward') {
            pushStack = this.cacheBackStack;
            popStack  = this.cacheForwardStack;
        } else {
            pushStack = this.cacheForwardStack;
            popStack  = this.cacheBackStack;
        }
    
        pushStack.push(id);
        id = popStack.pop();
        if (id) {
            delete this.cacheMapping[id];
        }
    
        this.trimCacheStack(pushStack, this.options.maxCacheLength);
    }

    private trimCacheStack(stack: any[], length: number) 
    {
        while (stack.length > length)
        {
            delete this.cacheMapping[stack.shift()]
        }
    }

    private findVersion() 
    {
        return $('meta').filter(function() {
            const name = $(this).attr('http-equiv');
            return name && name.toUpperCase() === 'X-PJAX-VERSION';
        }).attr('content');
    }
}



;(function($: any) {
    $.fn.pjax = function(selector, container, options?: PjaxOption) {
        return Pjax.bind(this, selector, container, options); 
    };
    $.pjax = function(option?: PjaxOption) {
        return Pjax.instance.get(option);
    };
})(jQuery);
