class WeightSearchBar {
    constructor(
        element: HTMLDivElement, 
        private option: {
            search_type: number,
            open_history: number,
            search_url: string,
            search_suggest_url: string,
            search_weight_id: string,
            search_tag: string,
            search_tag_text: string,
            base_uri: string,
        }
    ) {
        this.target = $(element);
        this.formTarget = this.target.find<HTMLFormElement>('form');
        this.inputTarget = this.target.find<HTMLInputElement>('input[type=text]');
        this.bindEvent();
        this.loadHistory();
    }

    private target: JQuery<HTMLDivElement>;
    private formTarget: JQuery<HTMLFormElement>;
    private inputTarget: JQuery<HTMLInputElement>;
    private suggestItems: any[] = [];
    private dropIndex = -1;
    private histories: string[] = [];
    private openType = 0;
    private historyKey = '';


    private bindEvent() {
        const that = this;
        this.target.on('click', '.search-btn', function() {

        }).on('click', '.clear-btn', function() {
            that.keywords = '';
        }).on('click', '.suggest-body li', function() {
            const $this = $(this);
            const item = that.suggestItems[$this.index()];
            that.keywords = this.formatTitle(item);
            this.dropIndex = this.suggestItems.indexOf(item);
            $this.addClass('active').siblings().removeClass('active');
            this.toggleOpenType(0);
        }).on('click', '.history-body li', function() {
            that.keywords = $(this).find('.item-text').text();
        }).on('click', '.history-body .clear-line', function(e) {
            e.stopPropagation();
            this.histories = [];
            this.saveHistory();
        }).on('click', '.history-body li .fa-times', function(e) {
            e.stopPropagation();
            const li = $(this).closest('li');
            that.histories.splice(li.index(), 1);
            this.saveHistory();
            li.remove();
        });
        this.inputTarget.on('keydown', function(e) {
            if (e.key === 'Enter') {
                that.tapConfirm();
                return;
            }
            if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') {
                that.dropIndex = -1;
                that.refreshSuggestion(that.keywords);
                return;
            }
            if (that.openType === 1) {
                that.moveDrop(that.suggestItems, e.key === 'ArrowUp');
            } else if (that.openType === 2) {
                that.moveDrop(that.histories, e.key === 'ArrowUp');
            }
        }).on('blur', function() {
            if (that.openType === 2) {
                that.toggleOpenType(0);
            }
        }).on('focus', function() {
            that.toggleOpenType(that.useHistory && that.histories.length > 0 ? 2 : 0);
        }).on('change', function() {
            that.target.find('.clear-btn').toggle(that.keywords.length > 0);
        });
    }

    public set keywords(v: string) {
        this.inputTarget.val(v).trigger('change');
    }

    public get keywords(): string {
        return this.inputTarget.val() as string;
    }

    private get useHistory(): boolean {
        return this.option.open_history > 0;
    }

    private moveDrop(items: any[], up = true) {
        if (items.length < 0) {
            return;
        }
        let i = this.dropIndex;
        if (up) {
            i = (i < 1 ? items.length: i) - 1;
        } else {
            i = i < items.length - 1 ? i + 1 : 0;
        }
        this.toggleDropIndex(i);
        this.keywords = this.formatTitle(items[i]);
    }

    private toggleDropIndex(i: number) {
        this.dropIndex = i;
        if (this.openType < 1) {
            return;
        }
        this.target.find(this.openType === 1 ? '.suggest-body li' : '.history-body li').each(function(index) {
            $(this).toggleClass('active', i === index);
        });

    }

    private tapConfirm() {
        const keywords = this.openType === 1 && this.dropIndex >= 0 ? this.suggestItems[this.dropIndex] : this.keywords;
        this.toggleOpenType(0);
        const text = this.formatTitle(keywords);
        this.addHistory(text);
        if (typeof keywords === 'object' && keywords.url) {
            window.location.href = keywords.url;
            return;
        }
        if (this.formTarget.length > 0) {
            this.formTarget.trigger('submit');
            return;
        }
        this.gotoSearch(text);
    }

    private suggest(items: any[]) {
        this.suggestItems = items;
        this.dropIndex = -1;
        this.toggleOpenType(items.length > 0 ? 1 : 0); 
        let html = '';
        items.forEach((item, i) => {
            this.renderSuggestItem(i + 1, item);
        });
        this.target.find('.suggest-body ul').html(html);
        this.toggleOpenType(1);
    }

    private formatTitle(item: any) {
        if (typeof item !== 'object') {
            return item;
        }
        return item.title || item.name;
    }

    private toggleOpenType(i: number) {
        this.openType = i;
        this.target.find('.suggest-body').toggle(i === 1);
        this.target.find('.history-body').toggle(this.useHistory && i === 2);
    }

    private refreshSuggestion(keywords: string) {
        const ul = this.target.find('.suggest-body ul');
        if (!keywords || keywords.length < 1) {
            ul.html('');
            this.toggleOpenType(0);
            return;
        }
        this.loadSuggest(keywords.trim(), data => {
            if (data.length === 0) {
                ul.html('');
                this.toggleOpenType(0);
                return;
            }
            this.suggest(data);
        });
    }

    private renderSuggestItem(i: number, item: any) {
        const text = this.formatTitle(item);
        return `<li>
        <span class="item-no">${i}</span>
        <span class="item-text">${text}</span>
    </li>`;
    }

    private renderHistoryItem(i: number, item: any) {
        return `<li>
        <i class="fa fa-history"></i>
        <span class="item-text">${item}</span>
        <i class="fa fa-times"></i>
    </li>`;
    }

    private addHistory(item: string) {
        if (!this.useHistory) {
            return;
        }
        item = item.trim();
        if (item.length < 1 || this.histories.indexOf(item) >= 0) {
            return;
        }
        this.histories.push(item);
        if (this.histories.length > 8) {
            this.histories.splice(8);
        }
        this.saveHistory();
    }

    private saveHistory() {
        if (!this.useHistory || !this.historyKey) {
            return;
        }
        if (this.histories.length === 0) {
            window.localStorage.removeItem(this.historyKey);
            return;
        }
        window.localStorage.setItem(this.historyKey, JSON.stringify(this.histories));
    }

    private loadHistory() {
        if (!this.useHistory || !this.historyKey) {
            return;
        }
        const text = window.localStorage.getItem(this.historyKey);
        if (!text) {
            return;
        }
        this.histories = JSON.parse(text) || [];
        let html = '';
        this.histories.forEach((item, i) => {
            this.renderHistoryItem(i + 1, item);
        });
        this.target.find('.history-body ul').html(html);
    }

    private gotoSearch(keywords: string) {
        if (this.option.search_type < 1) {
            window.location.href = this.option.search_url + (this.option.search_url.indexOf('?') >= 0 ? '&' : '?') + 'keywords=' + encodeURIComponent(keywords);
            return;
        }
        if (this.option.search_type == 1) {
            lazyWeight(this.option.search_weight_id, {keywords});
            return;
        }
        const that = this;
        $(this.option.search_tag).each(function() {
            const ele = $(this);
            const text = ele.find(that.option.search_tag_text).text();
            ele.toggle(that.isMatch(text, keywords));
        });
    }

    private isMatch(val: string, keywords?: string) {
        if (!keywords || val.indexOf(keywords) >= 0) {
            return true;
        }
        if (val.length <= keywords.length) {
            return false;
        }
        const wordsItem = keywords.split(' ');
        for (const word of wordsItem) {
            if (!word) {
                continue;
            }
            if (val.indexOf(word) >= 0) {
                return true;
            }
        }
        return false;
    }

    private loadSuggest(keywords: string, cb: (data: any[]) => void) {
        if (this.option.search_type < 1) {
            if (this.option.search_suggest_url) {
                $.getJSON(this.option.search_suggest_url, {
                    keywords
                }, data => {
                    if (data.code != 200) {
                        cb([]);
                        return;
                    }
                    cb(data.data);
                });
            }
            cb([]);
            return;
        }
        if (!keywords) {
            cb([]);
            return;
        }
        if (this.option.search_type == 1) {
            cb([]);
            return;
        }
        const that = this;
        const items = [];
        $(this.option.search_tag).each(function() {
            const ele = $(this);
            const text = ele.find(that.option.search_tag_text).text();
            if (that.isMatch(text, keywords)) {
                items.push(text);
            }
        });
        cb(items);
    }
}

function lazyWeight(weightId: string, queries: any) {
    const target = $(this.option.search_weight_id);
    if (target.length < 1) {
        return;
    }
    if (target.hasClass('template-lazy')) {
        lazyHtml(target, undefined, queries);
        return;
    }
    target.trigger('weight_query', queries);
}

function lazyHtml(element: JQuery<HTMLElement>, url?: string, data?: any) {
    if (element.length < 1) {
        return;
    }
    element.addClass('lazy-loading');
    const eleId = element.attr('id');
    if (!url) {
        url = element.attr('data-url');
    }
    if (!url) {
        return;
    }
    $.get(url, data, (html:string) => {
        if (eleId && html.indexOf(`id="${eleId}"`) > 0) {
            element.replaceAll(html);
            return;
        }
        element.removeClass('lazy-loading').removeClass('template-lazy');
        element.html(html);
        element.trigger('lazyLoaded');
    });
}

$(function() {
    const $window = $(window);
    const scrollDiff = 0;
    $window.on('scroll', () => {
        const items = $('.template-lazy');
        if (items.length <= 0) {
            return;
        }
        let height = $window.scrollTop();
        let bottom = $window.height() + height;
        items.each(function() {
            const element = $(this);
            if (element.hasClass('lazy-loading')) {
                return;
            }
            const top = element.offset().top;
            if (top + scrollDiff >= height && top < bottom) {
                lazyHtml(element);
            }
        });
    });
});