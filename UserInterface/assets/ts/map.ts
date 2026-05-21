/// <reference path="../../../node_modules/@types/bmapgl/index.d.ts" />
interface IMapBuilder {

    get selected(): {x: number, y: number};
    /**
     * 聚焦到
     * @param x 
     * @param y 
     */
    focus(x: number, y: number): IMapBuilder;
    /**
     * 移动到
     * @param x 
     * @param y 
     */
    moveTo(x: number, y: number): IMapBuilder;
    /**
     * 搜索进入城市
     * @param city 
     */
    search(city: string): IMapBuilder;

    /**
     * 在坐标标记
     * @param x 
     * @param y 
     * @param mark 
     */
    mark(x: number, y: number, mark?: string): IMapBuilder;

    /**
     * 清除所有悬浮物
     */
    clear(): IMapBuilder;
    /**
     * 绑定事件
     * @param event 
     * @param cb 
     */
    on(event: 'click', cb: (e: {x: number, y: number}) => void): IMapBuilder;

    /**
     * 开启定位
     * @param cb 
     */
    location(cb: (e: {x: number, y: number}) => void): IMapBuilder;
    /**
     * 截图
     */
    snapshot(cb: (image: string) => void): void;
}

class BaiduMapBuilder implements IMapBuilder {

    constructor(
        private element: string
    ) {
        this.lazyRender();
    }

    private instance?: BMapGL.Map;

    private current: {x: number, y: number} = {x:0, y: 0};

    public get selected(): {x: number, y: number} {
        return {...this.current};
    }

    public focus(x: number, y: number): IMapBuilder {
        this.current = {x, y};
        if (!this.instance) {
            this.renderIf(x, y);
            return this;
        }
        const point = new BMapGL.Point(x, y);
	    this.instance.centerAndZoom(point, 12);
        return this;
    }
    public mark(x: number, y: number, mark?: string): IMapBuilder {
        this.current = {x, y};
        this.renderIf(x, y);
        const point = new BMapGL.Point(x, y);
        const marker = new BMapGL.Marker(point);
        this.instance?.addOverlay(marker);
        if (mark) {
            const label = new BMapGL.Label(mark, {
                position: point,
                offset: new BMapGL.Size(15, -30),
            });
            this.instance?.addOverlay(label);
        }
        this.instance?.centerAndZoom(point, 12);
        return this;
    }

    public search(city: string): IMapBuilder {
        this.instance?.centerAndZoom(city);
        return this;
    }

    public moveTo(x: number, y: number): IMapBuilder {
        this.current = {x, y};
        const point = new BMapGL.Point(x, y);
        this.instance?.panTo(point);
        return this;
    }

    public location(cb: (e: {x: number, y: number}) => void): IMapBuilder {
        const geo = new BMapGL.Geolocation();
        geo.getCurrentPosition((r) => {
            if(geo.getStatus() == BMAP_STATUS_SUCCESS){
                this.current = {x: r.point.lng, y: r.point.lat};
                cb.call(this, this.current);
            }     
        }, {enableHighAccuracy: true});
        return this;
    }

    public snapshot(cb: (image: string) => void): void {

    }

    public on(event: 'click', cb: (e: {x: number, y: number}) => void): IMapBuilder {
        this.instance?.addEventListener(event, e => {
            this.current = {x: e.latlng.lng, y: e.latlng.lat};
            cb.call(this, this.current);
        });
        return this;
    }

    public clear(): IMapBuilder {
        this.instance?.clearOverlays();
        return this;
    }

    private lazyRender() {
        setTimeout(() => {
            this.renderIf(116.331398,39.897445);
        }, 500);
    }

    private renderIf(x: number, y: number) {
        if (this.instance) {
            return;
        }
        this.instance = new BMapGL.Map(this.element); 
        this.focus(x, y);
        this.instance.enableScrollWheelZoom();
    }

}

class MapBuilder {

    public static useBaidu(target: string): IMapBuilder {
        return new BaiduMapBuilder(target);
    }
}