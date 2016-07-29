export class Carousel {
    constructor(
        public element: JQuery,
        options?: CarouselOptions
    ) {
        this.options = $.extend({}, CarouselDefaultOptions, options)
        let items = this.element.find(options.itemTag);
        this._itemWidth = items.width();
        this._itemLength = items.length;
        if (!options.range) {
            options.range = this._itemWidth;
        }
        this._box = this.element.find(options.boxTag);
        this._width = items.width() * this._itemLength;
    }

    public options: CarouselOptions;

    private _width: number;

    private _itemWidth: number;

    private _itemLength: number;

    private _left: number;

    get left(): number {
        return this._left;
    }

    set left(left: number) {
        if (left > 0) {
            left = 0;
        } else if (left < -this._width) {
            left = -this._width;
        }
        if (left == this._left) {
            return;
        }
        this._left = left;
        this._box.animate({left: this._left + "px"}, this.options.animationTime, this.options.animationMode);
    }

    private _box: JQuery;

    public next(range: number = this.options.range) {
        this.left = Math.max(-this._width, this._left - range);
    }

    public previous(range: number = this.options.range) {
        this.left = Math.min(0, this._left + range);
    }

    public goto(index: number) {
        this.left = - index * this._itemWidth;
    }
    
}

export interface CarouselOptions {
     range?: number,
     itemTag?: string,
     boxTag?: string,
     animationTime?: any,
     animationMode?: string
}

class CarouselDefaultOptions implements CarouselOptions {
     itemTag: string = 'li';
     boxTag: string = '.box';
     animationTime: any = '1s';
     animationMode: string = 'slow';
}