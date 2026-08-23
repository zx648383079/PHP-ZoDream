class BeastTranslator {
    constructor(
        iv?: string[]|string
    ) {
        if (!iv) {
            return;
        }
        if (!Array.isArray(iv)) {
            iv = this.getIV(iv);
        }
        this.iv = iv;
    }

    private iv: string[] = ['嗷', '呜', '啊', '~'];

    public get isValid(): boolean {
        return this.validateIV(this.iv);
    }

    public encode(text: string): string {
        let iv = this.iv;
        if (!this.validateIV(iv)) {
            iv = ['嗷', '呜', '啊', '~'];
        }
        // 1. 将文本转为十六进制字符串（每个字符占4位）
        const hexParts: string[] = [];
        for (let i = 0; i < text.length; i++) {
            const codePoint = text.codePointAt(i) ?? 0;
            // 处理代理对（如 emoji）
            if (codePoint > 0xFFFF) {
                hexParts.push((codePoint & 0xFFFF).toString(16).padStart(4, '0'));
                // 跳过低代理项
                i++;
            } else {
                hexParts.push(codePoint.toString(16).padStart(4, '0'));
            }
        }
        const hex = hexParts.join('');

        // 2. 遍历十六进制字符进行编码
        let res = '';
        let offset = 0; // 动态偏移量

        for (let i = 0; i < hex.length; i++) {
            const char = hex[i];
            const value = parseInt(char, 16);
            // 加上动态偏移，确保结果在 0-15 范围内
            const k = (value + offset) % 16;
            
            // 将 0-15 拆分为两个索引 (0-3, 0-3)
            const idx1 = Math.floor(k / 4);
            const idx2 = k % 4;
            
            res += iv[idx1] + iv[idx2];
            offset++;
        }

        return `${iv[3]}${iv[1]}${iv[0]}${res}${iv[2]}`;
    }

    public decode(text: string, ignoreInvalid = false): string {
        // 1. 构建反向映射表：兽音字符 -> 索引
        let iv = [text[2], text[1], text[text.length - 1], text[0]]; //this.getIV(text);
        let hasSignature = true;
        if (!this.validateIV(iv)) {
            iv = this.getIV(text);
            hasSignature = false;
        }
        if (hasSignature) {
            text = text.substring(3, text.length - 1);
        }
        const charToIndex: Map<string, number> = new Map();
        for (let i = 0; i < iv.length; i++) {
            charToIndex.set(iv[i], i);
        }

        // 2. 每两个字符为一组，还原十六进制字符串
        const hexParts: string[] = [];
        let offset = 0; // 动态偏移量

        for (let i = 0; i < text.length; i += 2) {
            // 检查是否还有足够的字符
            if (i + 1 >= text.length) {
                if (ignoreInvalid) {
                    break;
                }
                throw new Error(`兽音字符串长度必须为偶数，当前位置: ${i}`);
            }

            const char1 = text[i];
            const char2 = text[i + 1];

            const idx1 = charToIndex.get(char1);
            const idx2 = charToIndex.get(char2);

            // 处理未识别的字符
            if (idx1 === undefined || idx2 === undefined) {
                if (ignoreInvalid) {
                    offset++;
                    continue;
                }
                const invalidChar = idx1 === undefined ? char1 : char2;
                throw new Error(`未识别的兽音字符: "${invalidChar}"`);
            }

            // 还原出 0-15 的数值
            let k = (idx1 * 4 + idx2) - offset;
            // 确保结果在 0-15 范围内（处理偏移导致的负数）
            k = ((k % 16) + 16) % 16;

            hexParts.push(k.toString(16));
            offset++;
        }

        // 3. 将十六进制字符串还原为文本
        const hex = hexParts.join('');
        const textParts: string[] = [];

        for (let i = 0; i < hex.length; i += 4) {
            const charHex = hex.slice(i, i + 4);
            // 如果最后一组不足4位，补0（正常情况下不会发生）
            const fullHex = charHex.padEnd(4, '0');
            const codePoint = parseInt(fullHex, 16);
            textParts.push(String.fromCodePoint(codePoint));
        }

        return textParts.join('');
    }

    private getIV(text: string): string[] {
        const res: string[] = [];
        let isDefault = true;
        for (let i = 0; i < text.length; i++) {
            const element = text[i];
            if (text.codePointAt(i)! <= 0x20) {
                // 空白字符跳过
                continue;
            }
            if (this.iv.indexOf(element) < 0) {
                isDefault = false;
            }
            if (res.indexOf(element) < 0) {
                res.push(element);
            }
        }
        return isDefault ? this.iv : res.sort();
    }

    private validateIV(iv: string[]): boolean {
        if (!Array.isArray(iv) || iv.length !== 4) {
            return false;
        }
        const unique = new Set(iv);
        if (unique.size !== 4) {
            return false;
        }
        return true;
    }
}