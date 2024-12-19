function isNumber(val: any): boolean {
    if (typeof val === 'number') {
        return true;
    }
    if (typeof val !== 'string') {
        return false;
    }
    return /^\d*(\.\d*)?$/.test(val);
}

class Encryptor {

    private lastTime: Date|undefined;
    private lastKeys: number[] = [];

    private get keyItems(): number[] {
        if (!this.lastTime) {
            this.lastTime = new Date();
            this.lastKeys = this.createKeys(this.lastTime);
        }
        return this.lastKeys;
    }

    public getCurrentTime(): string {
        const now = this.lastTime ?? new Date();
        this.lastTime = undefined;
        return `${now.getFullYear()}-${now.getMonth() + 1}-${now.getDate()} ${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`;
    }

    public encrypt(data: string): string {
        const keyItems = this.keyItems;
        return window.btoa(data).split('').map((code, i) => {
            return this.dictionaryCode(code.charCodeAt(0) - keyItems[i % keyItems.length]);
        }).join('');
    }

    public decrypt(data: string, timestamp: string|Date|number): string {
        let i = 0;
        let j = 0;
        const items = [];
        const keyItems = this.createKeys(timestamp);
        while (i < data.length) {
            let step = 1;
            if (!this.isDictionaryCode(data.charAt(i))) {
                step ++;
            }
            items.push(String.fromCharCode(this.dictionaryKey(data.substring(i, i + step) + keyItems[j % keyItems.length])));
            i += step;
            j ++;
        }
        return window.atob(items.join(''));
    }

    private createKeys(timestamp: string|Date|number): number[] {
        if (!isNumber(timestamp)) {
            if (typeof timestamp === 'string') {
                timestamp = new Date(timestamp);
            }
            if (timestamp instanceof Date) {
                timestamp = Math.floor(timestamp.getTime() / 1000);
            }
        }
        const items = timestamp.toString().substring(0, 10).split('').map(i => parseInt(i));
        const last = items[items.length - 1];
        const offset = last % 2;
        for (let i = 0; i < items.length; i += 2) {
            const pos = i + offset;
            if (pos >= items.length) {
                continue;
            }
            items[pos] = (items[pos] + last) % 10;
        }
        return items;
    }

    private dictionaryLength(): number {
        return 51;
    }

    private dictionaryCode(code: number): string {
        code -= 24;
        const rate = code % this.dictionaryLength();
        const prefix = code >= this.dictionaryLength() ? '0' : '';
        if (rate < 9) {
            return prefix + String.fromCharCode(rate + 49);
        }
        if (rate < 35) {
            return prefix + String.fromCharCode(rate + 88);
        }
        return prefix + String.fromCharCode(rate + 30);
    }

    private isDictionaryCode(code: string): boolean {
        return code !== '0';
    }

    private dictionaryKey(code: string): number {
        let base = 24;
        let i = 0;
        if (code.length > 1) {
            base += this.dictionaryLength();
            i = 1;
        }
        const ord = code.charCodeAt(i);
        if (ord <= 57) {
            return base + ord - 49;
        }
        if (ord <= 90) {
            return base + ord - 30;
        }
        return base + ord - 88;
    }
}
