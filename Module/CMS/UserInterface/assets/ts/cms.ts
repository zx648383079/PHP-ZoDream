interface IEngine {
    name: string,
    icon: string,
    url: string,
    suggest?: string
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
            suggest: '',
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
            name: 'Github',
            icon: 'icon-github',
            url: 'https://github.com/search?utf8=✓&q={word}',
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
        });
        this.box.on('click', '.search-icon', function(e) {
            e.stopPropagation();
            engineBox.show();
        }).on('keyup', '.search-input input', function(e: KeyboardEvent) {
            const keywords = $(this).val() as string;
            if (e.key === 'Enter') {
                that.tapSearch(keywords);
                return;
            }
            if (that.showTip) {
                that.refreshTip(keywords);
            }
        }).on('click', '.search-engine .search-engine-body li', function() {
            that.changeEngine($(this).index());
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
        $.ajax({
            url: 'https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd=' + encodeURI(keywords),
            dataType: 'jsonp',
            jsonp: 'cb'
        }).done(res => {
            ul.html(this.getTipList(res.s as string[] || []));
            box.show();
        }).fail(res => {
            ul.html('');
            box.hide();
        })
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

class Navigation {
    constructor(
        public box: JQuery,
        public groups: IGroup[] = []
    ) {
        this.refresh();
        this.bindEvent();
    }

    /**
     * bindEvent
     */
    public bindEvent() {
        this.box.on('click', '.custom-btn', function() {
            let box = $(this).closest('.self-box');
            box.toggleClass('edit-mode');
        }).on('click', '.panel-close', function() {
            $(this).closest('.self-box').removeClass('edit-mode');
        }).on('hover', '.site-item', function() {
            let box = $(this).closest('.self-box');
        });
    }

    /**
     * refresh
     */
    public refresh() {
        this.box.find('.panel-body').html(this.getHtml());
    }

    private getHtml() {
        let html = '';
        if (this.groups && this.groups.length < 1) {
            return html;
        }
        this.groups.forEach(group => {
            let ul = '';
            group.items.forEach(item => {
                ul += `<a href="${item.url}" target="_blank" class="site-item">
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

function bindNavigation() {
    let search = new Search($('.search-box')),
        navigate = new Navigation($('.self-box'));
}

$(function() {
    $(document).on('click', ".tab-box .tab-header .tab-item", function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        $this.closest(".tab-box").find(".tab-body .tab-item").eq($this.index()).addClass("active").siblings().removeClass("active");
    }).on('click', '.nav-bar .nav-toggle', function() {
        $(this).closest('.nav-bar').toggleClass('open');
    });
});