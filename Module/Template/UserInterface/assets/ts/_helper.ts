class EditorHelper {

    public static twoPad(i: number): string {
        return i < 10 && i >= 0 ? '0' + i : i.toString();
    }

    /**
    * 格式化数字
    * @param val 
    * @returns 
    */
    public static parseNumber(val: any): number {
        if (!val || isNaN(val)) {
            return 0;
        }
        if (typeof val === 'number') {
            return val;
        }
        if (typeof val === 'boolean') {
            return val ? 1 : 0;
        }
        if (typeof val !== 'string') {
            val = val.toString();
        }
        if (val.indexOf(',') > 0) {
            val = val.replace(/,/g, '');
        }
        if (val.indexOf('.') > 0) {
            val = parseFloat(val);
        } else {
            val = parseInt(val, 10);
        }
        return isNaN(val) ? 0 : val;
    }

    public static checkRange(val: number, min = 0, max = 100): number {
        if (val < min) {
            return min;
        }
        if (max > min && val > max) {
            return max;
        }
        return val;
    }

    /**
    * 深层次复制对象
    */
    public static cloneObject<T>(val: T): T {
        if (typeof val !== 'object') {
            return val;
        }
        if (val instanceof Array) {
            return val.map(item => {
                return EditorHelper.cloneObject(item);
            }) as any;
        }
        const res: any = {};
        for (const key in val) {
            if (Object.prototype.hasOwnProperty.call(val, key)) {
                res[key] = EditorHelper.cloneObject(val[key]);
            }
        }
        return res;
    }

    /**
    * 遍历对象属性或数组
    */
    public static eachObject(obj: any, cb: (val: any, key?: string|number) => any): any {
        if (typeof obj !== 'object') {
            return cb(obj, undefined);
        }
        if (obj instanceof Array) {
            for (let i = 0; i < obj.length; i++) {
                if (cb(obj[i], i) === false) {
                    return false;
                }
            }
            return;
        }
        for (const key in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, key)) {
                if (cb(obj[key], key) === false) {
                    return false;
                }
            }
        }
    }
}