class Base64 {

    private static chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
    private static lookup = new Uint8Array(256);
    private static booted = false;

    private static ready() {
        if (this.booted) {
            return;
        }
        this.booted = true;
        for (let i = 0; i < this.chars.length; i++) {
            this.lookup[this.chars.charCodeAt(i)] = i;
        }
    }
    
    public static encode(arraybuffer: ArrayBuffer): string {
        const bytes = new Uint8Array(arraybuffer);
        const len = bytes.length;
        let base64 = '';
      
        for (let i = 0; i < len; i += 3) {
            base64 += this.chars[bytes[i] >> 2];
            base64 += this.chars[((bytes[i] & 3) << 4) | (bytes[i + 1] >> 4)];
            base64 += this.chars[((bytes[i + 1] & 15) << 2) | (bytes[i + 2] >> 6)];
            base64 += this.chars[bytes[i + 2] & 63];
        }
      
        if (len % 3 === 2) {
            base64 = base64.substring(0, base64.length - 1);
        } else if (len % 3 === 1) {
            base64 = base64.substring(0, base64.length - 2);
        }
      
        return base64;
    }

    public static decode(base64: string): ArrayBuffer {
        const len = base64.length;
        const bufferLength = len * 0.75;
        const arraybuffer = new ArrayBuffer(bufferLength);
        const bytes = new Uint8Array(arraybuffer);
      
        let p = 0;
        for (let i = 0; i < len; i += 4) {
            const encoded1 = this.lookup[base64.charCodeAt(i)];
            const encoded2 = this.lookup[base64.charCodeAt(i + 1)];
            const encoded3 = this.lookup[base64.charCodeAt(i + 2)];
            const encoded4 = this.lookup[base64.charCodeAt(i + 3)];
        
            bytes[p++] = (encoded1 << 2) | (encoded2 >> 4);
            bytes[p++] = ((encoded2 & 15) << 4) | (encoded3 >> 2);
            bytes[p++] = ((encoded3 & 3) << 6) | (encoded4 & 63);
        }
      
        return arraybuffer;
    }

    public static toBuffer(val: string): ArrayBuffer {
        const items: number[] = [];
        for (let i = 0; i < val.length; i++) {
            items.push(val.charCodeAt(i));
        }
        return new Uint8Array(items);
    }
}