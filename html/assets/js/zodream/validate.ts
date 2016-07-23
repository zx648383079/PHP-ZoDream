export class Validate {
    public static email(arg: string): boolean {
        return this.isMatch('^\S+@\S+\.[\w]+', arg);
    }

    public static mobile(arg: string|number): boolean {
        return this.isMatch('^13[\d]{9}$|^14[57]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0678]{1}\d{8}$|^18[\d]{9}$', arg + '');
    }

    public static telephone(arg: string): boolean {
        return this.isMatch('^((\+?86)|(\(\+86\)))?\d{3,4}-\d{7,8}(-\d{3,4})?$', arg);
    }

    public static length(arg: string, min: number, max: number) {
        return arg.length >= min && arg.length <= max;
    }

    public static size(arg: number, min: number, max: number) {
        return arg >= min && arg <= max;
    }

    public static url(arg: string) {
        return this.isMatch('^((https?|ftp)?(://))?\S+\.\S+(/.*)?$', arg);
    }

    public static isMatch(pattern: string, arg: string) {
        let regex = new RegExp(pattern);
        return regex.test(arg);
    }
}