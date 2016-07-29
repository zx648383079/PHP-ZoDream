export class Page {
    constructor(
        public count: number,
        public size: number = 8
    ) {

    }

    public template: string = '<nav><ul class="pagination pagination-sm">{content}</ul></nav>';

    public activeTemplate:string = '<li class="active"><a href="#">{content}<span class="sr-only">(current)</span></a></li>';

    public pageTemplate: string = '<li><a href="{page}">{content}</a></li>';

    public firstTemplate: string = '';

    public lastTemplate:string = '';

    public nextTemplate: string = '<li><a href="{page}" aria-label="Next"> <span aria-hidden="true">&raquo;</span></a></li>';

    public previousTemplate: string = '<li> <a href="{content}" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a> </li>';

    public index: number = 1;

    private _fist(): string {
        if (this.index < 2) {
            return null;
        }
        return this.replace(1, 'First', this.firstTemplate);
    }

    private _last():string {
        if (this.index >= this.count) {
            return null;
        }
        return this.replace(this.count, 'Last', this.lastTemplate);
    }

    private _previous():string {
        if (this.index < 2) {
            return;
        }
        return this.replace(this.index - 1, 'Previous', this.previousTemplate);
    }

    private _next(): string {
        if (this.index > this.count - 1) {
            return null;
        }
        return this.replace(this.index + 1, 'Next', this.nextTemplate);
    }

    private _pageList(): string {
		if (this.count < 2) {
			return;
		}
		let linkPage:string = this.replace(1);
		let lastList= Math.floor(this.count / 2);
		let i = 0;
		let length = 0;
		if (this.size < this.count || this.index- lastList< 2 || this.size - this.count < 2) {
			i = 2;
			if (this.size <= this.count) {
				length = this.size - 1;
			} else {
				length = this.count;
			}
		} else if (this.index - lastList >= 2 && this.index + lastList <= this.size) {
			i = this.index- lastList;
			length = this.index + lastList- 1;
		} else if (this.index + lastList> this.size) {
			i = this.size - this.count + 1;
			length = this.size - 1;
		}
		if (this.index > lastList+ 1 && i > 2) {
			linkPage  += this.replace(null, '...', this.activeTemplate);
		}
		for (; i <= length; i ++) {
			linkPage += this.replace(i);
		}
		if (this.index < this.size- lastList && length < this.size - 1) {
			linkPage += this.replace(null, '...', this.activeTemplate);
		}
		linkPage += this.replace(this.size);
		return linkPage;
	}

    public replace(page: number = 1, content: string|number = 0, template: string = this.pageTemplate):string {
        if (!template) {
            return null;
        }
        if (content == 0) {
            content = page;
        }
        return template.replace("{page}", page + '').replace('content', content + '');
    }

    public create() {
        return this.template.replace('{content}', this._fist() + this._previous() + this._pageList() + this._next() + this._last());
    }
}