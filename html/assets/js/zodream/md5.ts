/**
 * 起始值
 */
const InitialBytes: Array<number> = [1732584193, -271733879, -1732584194, 271733878];

class MD5 {
    constructor(
        public value: string, 
        public key?: string, 
        public raw?: boolean) {
        
    }

    public parse(value: string = this.value, key: string = this.key, raw: boolean = this.raw): string {
        if (!key) {
            return raw ? this.rawMD5(value) : this.hexMD5(value);
        }
        return raw ? this.rawHmacMD5(key, value) : this.hexHmacMD5(key, value);
    }

    /*
    * Add integers, wrapping at 2^32. This uses 16-bit operations internally
    * to work around bugs in some JS interpreters.
    */
    private safeAdd (x: number, y: number):number {
        let lsw:number = (x & 0xFFFF) + (y & 0xFFFF), msw: number = (x >> 16) + (y >> 16) + (lsw >> 16);
        return (msw << 16) | (lsw & 0xFFFF);
    }

    /*
    * Bitwise rotate a 32-bit number to the left.
    */
    private bitRol (num: number, cnt: number): number {
        return (num << cnt) | (num >>> (32 - cnt));
    }

    /*
    * These privates implement the four basic operations the algorithm uses.
    */
    private MD5Cmn (q: number, a: number, b: number, x: number, s: number, t: number): number {
        return this.safeAdd(this.bitRol(this.safeAdd(this.safeAdd(a, q), this.safeAdd(x, t)), s), b);
    }
    private MD5FF (a:number, b:number, c: number, d: number, x: number, s: number, t: number): number {
        return this.MD5Cmn((b & c) | ((~b) & d), a, b, x, s, t);
    }
    
    private MD5GG (a:number, b: number, c: number, d: number, x: number, s: number, t: number): number {
        return this.MD5Cmn((b & d) | (c & (~d)), a, b, x, s, t);
    }
    private MD5HH (a:number, b: number, c: number, d: number, x: number, s: number, t: number): number {
        return this.MD5Cmn(b ^ c ^ d, a, b, x, s, t);
    }
    private MD5II (a:number, b: number, c: number, d: number, x: number, s: number, t: number): number {
        return this.MD5Cmn(c ^ (b | (~d)), a, b, x, s, t);
    }

    /*
    * Calculate the MD5 of an array of little-endian words, and a bit length.
    */
    private bytesMD5 (x: Array<number>, len: number): Array<number> {
        /* append padding */
        x[len >> 5] |= 0x80 << (len % 32);
        x[(((len + 64) >>> 9) << 4) + 14] = len;

        return this.appendBytesMd5(x, InitialBytes);
    }

    private appendBytesMd5(x: Array<number>, hash: Array<number> = InitialBytes): Array<number> {
        let i: number, olda:number, oldb: number, oldc: number, oldd: number,a:number = hash[0], b: number = hash[1], c: number = hash[2], d: number = hash[3];

        for (i = 0; i < x.length; i += 16) {
            olda = a;
            oldb = b;
            oldc = c;
            oldd = d;

            a = this.MD5FF(a, b, c, d, x[i], 7, -680876936);
            d = this.MD5FF(d, a, b, c, x[i + 1], 12, -389564586);
            c = this.MD5FF(c, d, a, b, x[i + 2], 17, 606105819);
            b = this.MD5FF(b, c, d, a, x[i + 3], 22, -1044525330);
            a = this.MD5FF(a, b, c, d, x[i + 4], 7, -176418897);
            d = this.MD5FF(d, a, b, c, x[i + 5], 12, 1200080426);
            c = this.MD5FF(c, d, a, b, x[i + 6], 17, -1473231341);
            b = this.MD5FF(b, c, d, a, x[i + 7], 22, -45705983);
            a = this.MD5FF(a, b, c, d, x[i + 8], 7, 1770035416);
            d = this.MD5FF(d, a, b, c, x[i + 9], 12, -1958414417);
            c = this.MD5FF(c, d, a, b, x[i + 10], 17, -42063);
            b = this.MD5FF(b, c, d, a, x[i + 11], 22, -1990404162);
            a = this.MD5FF(a, b, c, d, x[i + 12], 7, 1804603682);
            d = this.MD5FF(d, a, b, c, x[i + 13], 12, -40341101);
            c = this.MD5FF(c, d, a, b, x[i + 14], 17, -1502002290);
            b = this.MD5FF(b, c, d, a, x[i + 15], 22, 1236535329);

            a = this.MD5GG(a, b, c, d, x[i + 1], 5, -165796510);
            d = this.MD5GG(d, a, b, c, x[i + 6], 9, -1069501632);
            c = this.MD5GG(c, d, a, b, x[i + 11], 14, 643717713);
            b = this.MD5GG(b, c, d, a, x[i], 20, -373897302);
            a = this.MD5GG(a, b, c, d, x[i + 5], 5, -701558691);
            d = this.MD5GG(d, a, b, c, x[i + 10], 9, 38016083);
            c = this.MD5GG(c, d, a, b, x[i + 15], 14, -660478335);
            b = this.MD5GG(b, c, d, a, x[i + 4], 20, -405537848);
            a = this.MD5GG(a, b, c, d, x[i + 9], 5, 568446438);
            d = this.MD5GG(d, a, b, c, x[i + 14], 9, -1019803690);
            c = this.MD5GG(c, d, a, b, x[i + 3], 14, -187363961);
            b = this.MD5GG(b, c, d, a, x[i + 8], 20, 1163531501);
            a = this.MD5GG(a, b, c, d, x[i + 13], 5, -1444681467);
            d = this.MD5GG(d, a, b, c, x[i + 2], 9, -51403784);
            c = this.MD5GG(c, d, a, b, x[i + 7], 14, 1735328473);
            b = this.MD5GG(b, c, d, a, x[i + 12], 20, -1926607734);

            a = this.MD5HH(a, b, c, d, x[i + 5], 4, -378558);
            d = this.MD5HH(d, a, b, c, x[i + 8], 11, -2022574463);
            c = this.MD5HH(c, d, a, b, x[i + 11], 16, 1839030562);
            b = this.MD5HH(b, c, d, a, x[i + 14], 23, -35309556);
            a = this.MD5HH(a, b, c, d, x[i + 1], 4, -1530992060);
            d = this.MD5HH(d, a, b, c, x[i + 4], 11, 1272893353);
            c = this.MD5HH(c, d, a, b, x[i + 7], 16, -155497632);
            b = this.MD5HH(b, c, d, a, x[i + 10], 23, -1094730640);
            a = this.MD5HH(a, b, c, d, x[i + 13], 4, 681279174);
            d = this.MD5HH(d, a, b, c, x[i], 11, -358537222);
            c = this.MD5HH(c, d, a, b, x[i + 3], 16, -722521979);
            b = this.MD5HH(b, c, d, a, x[i + 6], 23, 76029189);
            a = this.MD5HH(a, b, c, d, x[i + 9], 4, -640364487);
            d = this.MD5HH(d, a, b, c, x[i + 12], 11, -421815835);
            c = this.MD5HH(c, d, a, b, x[i + 15], 16, 530742520);
            b = this.MD5HH(b, c, d, a, x[i + 2], 23, -995338651);

            a = this.MD5II(a, b, c, d, x[i], 6, -198630844);
            d = this.MD5II(d, a, b, c, x[i + 7], 10, 1126891415);
            c = this.MD5II(c, d, a, b, x[i + 14], 15, -1416354905);
            b = this.MD5II(b, c, d, a, x[i + 5], 21, -57434055);
            a = this.MD5II(a, b, c, d, x[i + 12], 6, 1700485571);
            d = this.MD5II(d, a, b, c, x[i + 3], 10, -1894986606);
            c = this.MD5II(c, d, a, b, x[i + 10], 15, -1051523);
            b = this.MD5II(b, c, d, a, x[i + 1], 21, -2054922799);
            a = this.MD5II(a, b, c, d, x[i + 8], 6, 1873313359);
            d = this.MD5II(d, a, b, c, x[i + 15], 10, -30611744);
            c = this.MD5II(c, d, a, b, x[i + 6], 15, -1560198380);
            b = this.MD5II(b, c, d, a, x[i + 13], 21, 1309151649);
            a = this.MD5II(a, b, c, d, x[i + 4], 6, -145523070);
            d = this.MD5II(d, a, b, c, x[i + 11], 10, -1120210379);
            c = this.MD5II(c, d, a, b, x[i + 2], 15, 718787259);
            b = this.MD5II(b, c, d, a, x[i + 9], 21, -343485551);

            a = this.safeAdd(a, olda);
            b = this.safeAdd(b, oldb);
            c = this.safeAdd(c, oldc);
            d = this.safeAdd(d, oldd);
        }
        return [a, b, c, d]
    }

    /*
    * Convert an array of little-endian words to a string
    */
    private bytesToWords (input: Array<number>): string {
        let i:number, output: string = '';
        for (i = 0; i < input.length * 32; i += 8) {
            output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xFF);
        }
        return output;
    }

    /*
    * Convert a raw string to an array of little-endian words
    * Characters >255 have their high-byte silently ignored.
    */
    private wordsToBytes (input: string): Array<number> {
        let i: number, output: Array<number> = [];
        output[(input.length >> 2) - 1] = undefined;
        for (i = 0; i < output.length; i += 1) {
            output[i] = 0;
        }
        for (i = 0; i < input.length * 8; i += 8) {
            output[i >> 5] |= (input.charCodeAt(i / 8) & 0xFF) << (i % 32);
        }
        return output;
    }

    /*
    * Calculate the MD5 of a raw string
    */
    private rstrMD5 (s: string): string {
        return this.bytesToWords(this.bytesMD5(this.wordsToBytes(s), s.length * 8));
    }

    /*
    * Calculate the HMAC-MD5, of a key and some data (raw strings)
    */
    private rstrHmacMD5 (key: string, data: string): string {
        let i: number, bkey: Array<number> = this.wordsToBytes(key), ipad: Array<number> = [], opad: Array<number> = [], hash: Array<number>;
        ipad[15] = opad[15] = undefined;
        if (bkey.length > 16) {
            bkey = this.bytesMD5(bkey, key.length * 8);
        }
        for (i = 0; i < 16; i += 1) {
            ipad[i] = bkey[i] ^ 0x36363636;
            opad[i] = bkey[i] ^ 0x5C5C5C5C;
        }
        hash = this.bytesMD5(ipad.concat(this.wordsToBytes(data)), 512 + data.length * 8);
        return this.bytesToWords(this.bytesMD5(opad.concat(hash), 512 + 128));
    }

    /*
    * Convert a raw string to a hex string
    */
    private rawToHex (input: string): string {
        let hexTab:string = '0123456789abcdef', output:string = '', x: number, i: number;
        for (i = 0; i < input.length; i += 1) {
            x = input.charCodeAt(i);
            output += hexTab.charAt((x >>> 4) & 0x0F) + hexTab.charAt(x & 0x0F);
        }
        return output;
    }

    /*
    * Encode a string as utf-8
    */
    private toRawUtf8 (input:string): string {
        return decodeURI(encodeURIComponent(input));
    }

    /*
    * Take string arguments and return either raw or hex encoded strings
    */
    private rawMD5 (s: string): string {
        return this.rstrMD5(this.toRawUtf8(s));
    }
    private hexMD5 (s:string): string {
        return this.rawToHex(this.rawMD5(s));
    }
    private rawHmacMD5 (k: string, d: string): string {
        return this.rstrHmacMD5(this.toRawUtf8(k), this.toRawUtf8(d));
    }
    private hexHmacMD5 (k: string, d:string):string {
        return this.rawToHex(this.rawHmacMD5(k, d));
    }

}