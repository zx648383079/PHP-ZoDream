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

    public static colorToRgba(color: string): IRgbaColor {
        const code = color.charAt(0);
        const matchFunc = (val: string): number[] => {
            return val.substring(val.indexOf('(') + 1, val.indexOf(')')).split(',').map(i => toFloat(i));
        };
        if (code === 'h') {
            const items = matchFunc(color);
            return this.hslToRgba({h: items[0], s: items[1], l: items[2], a: 1});
        } else if (code === 'r') {
            const items = matchFunc(color);
            if (items.length > 3) {
                return {r: items[0], g: items[1], b: items[2], a: items[3]};
            }
            return {r: items[0], g: items[1], b: items[2], a: 1};
        }
        return this.hexToRgba(color);
    }

    public static rgbaToHex(color: IRgbaColor): string {
        return ['#', color.r.toString(16), color.g.toString(16), color.b.toString(16), color.a >= 0 && color.a < 1 ? Math.round(color.a * 255).toString(16) : ''].join('');
    }

    public static hexToRgba(hex: string): IRgbaColor {
        if (hex.charAt(0) === '#') {
            hex = hex.substring(1);
        }
        let r: number, g: number, b: number = 0;
        let a: number = 1;
        const toHex = (val: string, isDouble = false) => {
            if (!val) {
                return 0;
            }
            const i = parseInt(val, 16);
            if (!isDouble) {
                return i;
            }
            return i * 17;
        };
        const step = hex.length <= 4 ? 1 : 2;
        r = toHex(hex.substring(0, step), step === 2);
        g = toHex(hex.substring(step, step * 2), step === 2);
        b = toHex(hex.substring(step * 2, step * 3), step === 2);
        if (hex.length > step * 3) {
            a = toHex(hex.substring(step * 3, step * 4), step === 2);
        }
        return { r, g, b, a };
    }

    public static rgbaToHsl(color: IRgbaColor): IHslColor {
        let r = color.r;
        let g = color.g;
        let b = color.b;
        r /= 255, g /= 255, b /= 255;
        let max = Math.max(r, g, b), min = Math.min(r, g, b);
        let h: number, s: number, l: number = (max + min) / 2;
        if (max == min) {
            h = s = 0; // achromatic
        } else {
            let d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                case g: h = (b - r) / d + 2; break;
                case b: h = (r - g) / d + 4; break;
            }
            h /= 6;
        }
        return { h, s, l, a: color.a};
    }

    public static hslToRgba(color: IHslColor): IRgbaColor {
        let h = color.h / 360, s = color.s / 100, l = color.l / 100;
        let r: number, g: number, b: number;
        if (s === 0) {
            r = g = b = l; // achromatic
        } else {
            const hue2rgb = (p: number, q: number, t: number) => {
                if (t < 0) t += 1;
                if (t > 1) t -= 1;
                if (t < 1 / 6) return p + (q - p) * 6 * t;
                if (t < 1 / 2) return q;
                if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
                return p;
            }
            let q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            let p = 2 * l - q;
            r = hue2rgb(p, q, h + 1 / 3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1 / 3);
        }
        return { r: Math.round(r * 255), g: Math.round(g * 255), b: Math.round(b * 255), a: color.a };
    }
}