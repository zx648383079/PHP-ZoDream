type ISUGGEST = (this: Search, keywords: string, cb: (data: string[]) => void) => void;

interface IEngine {
    name: string,
    icon: string,
    url: string,
    suggest?: string | ISUGGEST
}

class Search {
    constructor(
        public box: JQuery
    ) {
        this.refreshEngine();
        this.refreshDefault();
        this.bindEvent();
    }

    private engine = 0;

    private showTip = true;

    public readonly SEARCH_ENGINE: IEngine[] = [
        {
            name: '百度',
            icon: 'icon-baidu',
            url: 'https://www.baidu.com/s?wd={word}',
            suggest: function(keywords, cb) {
                this.jsonp('https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd=' + keywords, res => {
                    cb(res.s);
                })
            },
        },
        {
            name: 'Bing',
            icon: 'icon-bing',
            url: 'https://cn.bing.com/search?q={word}',
        },
        {
            name: 'Google',
            icon: 'icon-google',
            url: 'https://www.google.com/search?q={word}',
        },
        {
            name: 'DuckDuckGo',
            icon: 'icon-duckduckgo',
            url: 'https://duckduckgo.com/?q={word}',
            suggest: function(keywords, cb) {
                this.jsonp('https://duckduckgo.com/ac/?q=' + keywords, res => {
                    cb(this.pluck(res, 'phrase'));
                }, 'callback');
            },
        },
        {
            name: 'Github',
            icon: 'icon-github',
            url: 'https://github.com/search?utf8=✓&q={word}',
        },
        {
            name: '哔哩哔哩',
            icon: 'icon-bilibili',
            url: 'https://search.bilibili.com/all?keyword={word}',
            suggest: function(keywords, cb) {
                this.jsonp('https://s.search.bilibili.com/main/suggest?func=suggest&suggest_type=accurate&sub_type=tag&main_ver=v1&highlight=&userid=0&bangumi_acc_num=1&special_acc_num=1&topic_acc_num=1&upuser_acc_num=3&tag_num=10&special_num=10&bangumi_num=10&upuser_num=3&term=' + keywords, res => {
                    if (!res || !res.result.tag) {
                        return cb([]);
                    }
                    return cb(this.pluck(res.result.tag, 'value'));
                }, 'jsoncallback')
            }
        },
        {
            name: '淘宝',
            icon: 'icon-taobao',
            url: 'https://s.taobao.com/search?q={word}',
        },
        {
            name: '京东',
            icon: 'icon-jd',
            url: 'https://search.jd.com/Search?keyword={word}',
        }
    ]

    public bindEvent() {
        let that = this;
        let isEngine = false;
        const engineBox = this.box.find(".search-engine");
        engineBox.click(function(e) {
            e.stopPropagation();
        }).on('click', '.toggle-box', function(e) {
            $(this).toggleClass('checked');
            that.showTip = $(this).hasClass('checked');
        }).on('click', '.search-engine-body li', function() {
            that.changeEngine($(this).index());
            engineBox.hide();
        });
        this.box.on('click', '.search-icon', function(e) {
            e.stopPropagation();
            engineBox.show();
        }).on('keyup', '.search-input input', function(e) {
            let $this = $(this);
            const keywords = $this.val() as string;
            if (e.key === 'Enter') {
                that.tapSearch(keywords);
                return;
            }
            if (!that.showTip) {
                return;
            }
            if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') {
                that.refreshTip(keywords);
                return;
            }
            const items = that.box.find('.search-tips li');
            if (items.length < 0) {
                return;
            }
            let i = -1;
            for (let j = 0; j < items.length; j++) {
                const element = $(items[j]);
                if (element.hasClass('active')) {
                    i = j;
                    element.removeClass('active');
                    break;
                }
            }
            if (e.key === 'ArrowDown') {
                i = i < items.length - 1 ? i + 1 : 0;
            } else if (e.key === 'ArrowUp') {
                i = i < 1 ? items.length - 1 : i;
            }
            const element = items.eq(i);
            element.addClass('active');
            $this.val(element.text().replace(/^\d+/, ''))
        }).on('click', '.search-input input', function(e) {
            e.stopPropagation();
            engineBox.hide();
        }).on('click', '.search-tips li', function() {
            that.tapSearch($(this).text().replace(/^\d+/, ''));
        });
        $(document).click(function() {
            that.box.find('.search-tips').hide();
            engineBox.hide();
        });
    }

    /**
     * changeEngine
     */
    public changeEngine(i: number) {
        this.engine = i;
        this.refreshDefault();
    }

    /**
     * tapSearch
     */
    public tapSearch(keywords: string) {
        keywords = keywords.trim();
        if (!keywords || keywords.length < 1) {
            return;
        }
        this.box.find('.search-tips').hide();
        const engine = this.SEARCH_ENGINE[this.engine];
        const url = engine.url.replace('{word}', encodeURI(keywords.trim()));
        window.open(url, '_blank');
    }

    public refreshDefault() {
        const engine = this.SEARCH_ENGINE[this.engine];
        this.box.find('.search-icon').attr('class', 'search-icon ' + engine.icon);
    }

    public refreshTip(keywords: string) {
        const box = this.box.find('.search-tips');
        const ul = box.find('ul');
        if (!keywords || keywords.length < 1) {
            ul.html('');
            box.hide();
            return;
        }
        const engine = this.SEARCH_ENGINE[this.engine];
        const suggest = !engine.suggest ? this.SEARCH_ENGINE[0].suggest : engine.suggest;
        keywords = encodeURI(keywords);
        if (typeof suggest == 'string') {
            this.jsonp(suggest + keywords, res => {
                if (!res || !res.data || res.data.length < 1) {
                    ul.html('');
                    box.hide();
                    return;
                }
                ul.html(this.getTipList(res.data as string[] || []));
                box.show();
            });
            return;
        }
        suggest.call(this, keywords, res => {
            if (!res || res.length < 1) {
                ul.html('');
                box.hide();
                return;
            }
            ul.html(this.getTipList(res as string[] || []));
            box.show();
        });
    }

    /**
     * pluck
     */
    public pluck(data: any, key: string): string[] {
        let args = [];
        if (!data) {
            return args;
        }
        $.each(data, function() {
            if (this[key]) {
                args.push(this[key]);
            }
        });
        return args;
    }

    public jsonp(url: string, cb: Function, cbName: string = 'cb') {
        $.ajax({
            url,
            dataType: 'jsonp',
            jsonp: cbName
        }).done(res => {
            cb(res);
        }).fail(() => {
            cb()
        });
    }

    public refreshEngine() {
        this.box.find('.search-engine-body').html(this.getEngineList());
    }

    private getEngineList() {
        let html = '';
        this.SEARCH_ENGINE.forEach(item => {
            html += `<li><span class="${item.icon}"></span>${item.name}</li>`;
        });
        return html;
    }

    private getTipList(data: string[]) {
        let html = '';
        data.forEach((item, i) => {
            i += 1;
            html += `<li><span>${i}</span> ${item}</li>`;
        });
        return html;
    }
}

interface ISiteItem {
    name: string,
    url: string,
    icon?: string
}

interface IGroup {
    name: string,
    items: ISiteItem[],
}

const NAV_COOKIE = 'nav_self';

class Navigation {
    constructor(
        public box: JQuery,
        public groups: IGroup[] = []
    ) {
        this.dialog = $('#add-box').dialog();
        this.load();
        this.refresh();
        this.bindEvent();
    }

    public dialog: any;

    private editData = [];

    /**
     * load
     */
    public load() {
        if (this.groups.length > 0) {
            return;
        }
        let data = window.localStorage.getItem(NAV_COOKIE);
        if (!data || data.indexOf('{') < 0) {
            return;
        }
        this.groups = JSON.parse(data);
    }

    /**
     * bindEvent
     */
    public bindEvent() {
        let that = this;
        this.box.on('click', '.custom-btn', function() {
            let box = $(this).closest('.self-box');
            box.toggleClass('edit-mode');
        }).on('click', '.panel-close', function() {
            $(this).closest('.self-box').removeClass('edit-mode');
        }).on('click', '.site-item', function(e) {
            let box = $(this).closest('.self-box');
            if (!box.hasClass('edit-mode')) {
                return;
            }
            e.preventDefault();
        }).on('click', '.site-item .fa-times', function(e) {
            that.deleteByA($(this).closest('.site-item'));
        }).on('click', '.site-item .fa-edit', function(e) {
            that.editByA($(this).closest('.site-item'));
        }).on('click', '.add-btn', function() {
            that.showDialog({
                group: '作为分组名',
                name: '',
                link: ''
            });
        }).on('click', '.panel-footer .btn', function() {
            const text = $(this).text();
            if (text.indexOf('本') >= 0) {
                window.localStorage.setItem(NAV_COOKIE, JSON.stringify(that.groups));
                Dialog.tip('本地保存成功！');
                return;
            }
            if (text.indexOf('恢') >= 0) {
                that.groups = [];
                window.localStorage.removeItem(NAV_COOKIE);
                Dialog.tip('清空成功！');
                that.saveAsync();
                return;
            }
            if (text.indexOf('云') >= 0) {
                that.saveAsync();
                return;
            }
        });
        this.dialog.find('select').change(function() {
            let val = $(this).val();
            that.dialog.find('.input-group').eq(2).toggle(val !== '作为分组名');
        });
        this.dialog.on('done', function() {
            const data = {
                group: this.find('select').val(),
                name: this.find('[name=name]').val(),
                link: this.find('[name=link]').val()
            };
            if (data.group == '作为分组名') {
                data.group == '';
            } else if (!data.link || data.link.indexOf('//') < 0) {
                alert('请输入完整的网址');
                return;
            }
            that.save(data);
            this.close();
        });
    }

    /**
     * deleteByA
     */
    public deleteByA(item: JQuery) {
        const i = item.closest('.group-item').index();
        const j = item.index();
        this.groups[i].items.splice(j, 1);
        item.remove();
    }

    public editByA(item: JQuery) {
        const i = item.closest('.group-item').index();
        const j = item.index();
        this.editData = [i, j];
        this.showDialog({
            group: this.groups[i].name,
            name: this.groups[i].items[j].name,
            link: this.groups[i].items[j].url
        });
    }

    /**
     * getItemByA
     */
    public getItemByA(item: JQuery): ISiteItem {
        const i = item.closest('.group-item').index();
        const j = item.index();
        return this.groups[i].items[j];
    }

    /**
     * save
     */
    public save(data: any) {
        if (data.group == '' || data.group == '作为分组名') {
            this.saveGroup(data.name);
        } else {
            this.saveSite(data);
        }
        this.refresh();
    }

    public saveAsync() {
        $.post('form/save', {
            model: 'navigation',
            content: JSON.stringify(this.groups),
            title: '我的导航',
        }, res => {
            if (res.code == 200) {
                Dialog.tip('保存成功');
                return;
            }
            Dialog.tip(res.errors);
        }, 'json');
    }

    private addSite(data: any) {
        for (const group of this.groups) {
            if (group.name == data.group) {
                group.items.push({
                    name: data.name,
                    url: data.link
                });
            }
        }
    }

    private saveSite(data: any) {
        if (this.editData.length === 2) {
            let [i, j] = this.editData;
            if (data.group === this.groups[i].name) {
                this.groups[i].items[j] = {
                    name: data.name,
                    url: data.link
                };
            } else {
                this.groups[i].items.splice(j, 1);
                this.addSite(data);
            }
        } else {
            this.addSite(data);
        }
        this.editData = [];
    }

    private saveGroup(name) {
        this.groups.push({
            name,
            items: []
        });
    }

    public showDialog(options: any) {
        let html = '<option>作为分组名</option>';
        for (const item of this.groups) {
            html += '<option>'+ item.name +'</option>';
        }
        this.dialog.find('select').html(html);
        for (const key in options) {
            if (options.hasOwnProperty(key)) {
                this.dialog.find('[name='+key+']').val(options[key]);
            }
        }
        this.dialog.find('select').trigger('change');
        this.dialog.show();
    }

    /**
     * refresh
     */
    public refresh() {
        this.box.find('.panel-body').html(this.getHtml());
    }

    private getHtml() {
        let html = '';
        if (!this.groups || this.groups.length < 1) {
            return html;
        }
        this.groups.forEach(group => {
            let ul = '';
            group.items.forEach(item => {
                ul += `<a href="${item.url}" target="_blank" class="site-item" rel="noopener noreferrer">
                <i class="fa fa-times"></i>
                ${item.name}
                <i class="fa fa-edit"></i>
            </a>`
            });
            html += `<div class="group-item">
            <div class="group-name">${group.name}</div>
            <div>
                ${ul}
            </div>
        </div>`;
        });
        return html;
    }
}

function bindNavigation(data?: any) {
    let search = new Search($('.search-box')),
        navigate = new Navigation($('.self-box'), data);
}

function bindTheme() {
    $('.theme-list .item').mouseenter(function() {
        let img = $(this).find('.thumb img');
        let height = img.closest('.thumb').innerHeight();
        let h = img.height();
        if (h <= height) {
            return;
        }
        img.css('top', (height - h) + 'px');
    }).mouseleave(function() {
        $(this).find('.thumb img').css('top', 0);
    });
}

$(function() {
    $(document).on('click', ".tab-box .tab-header .tab-item", function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        $this.closest(".tab-box").find(".tab-body .tab-item").eq($this.index()).addClass("active").siblings().removeClass("active");
    }).on('click', '.nav-bar .nav-toggle', function() {
        $(this).closest('.nav-bar').toggleClass('open');
    });
    $('img.lazy').lazyload({
        callback: 'img'
    });
});