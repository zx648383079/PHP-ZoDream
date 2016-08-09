namespace Helper {
    export class StringExpand {
        public static pad(arg: number, length: number): string {
            let argString = arg.toString();
            let len: number = argString.length;
            while(len < length) {  
                argString = "0" + argString;
                len++;  
            }
            return argString;  
        }
    }

    export class Random {

        public static getNumber(min: number, max: number): number {
            max ++;
            return Math.floor(Math.random() * (max - min)) + min;
        }

        public static getNumberByLength(length: number): string {
            return StringExpand.pad(Math.floor(Math.random() * Math.pow(10, length)), length);
        }

        public static getWord(length: number): string {
            const map: Array<string> = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            let arg:string = "",
                len: number = 0;
            while (length > len) {
                arg += map[this.getNumber(0, 62)];
                len ++;
            }
            return arg;
        }
        public static getGuid(): string {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }
    }

    export class Request {
        public static queryString(): string {
            let s1: Array<string>;
            let q: any = {};
            let s: Array<string> = document.location.search.substring(1).split("&");
            for (let i = 0, l = s.length; i < l; i++) {
                s1 = s[i].split("=");
                if (s1.length > 1) {
                    var t = s1[1].replace(/\+/g, " ")
                    try {
                        q[s1[0]] = decodeURIComponent(t)
                    } catch (e) {
                        q[s1[0]] = decodeURI(t)
                    }
                }
            }
            return q;
        }
        public static url(): string {
            return window.location.href;
        }
        public static urlEncode(str: string): string {
            if (str == null) return "";
            return str.replace(/\+/g, encodeURI("%2B"));
        }
        public static domain(): string {
            return window.location.host;
        }
        public static back() {
            window.history.go(-1);
        }
            //打印
        public static print(id: string) {
            let el = document.getElementById(id);
            let iframe = document.createElement('iframe');
            let doc:HTMLDocument = null;
            iframe.setAttribute('style', 'position:absolute;width:0px;height:0px;left:-500px;top:-500px;');
            document.body.appendChild(iframe);
            doc = iframe.contentWindow.document;
            doc.write('<div>' + el.innerHTML + '</div>');
            doc.close();
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            if (navigator.userAgent.indexOf("MSIE") > 0) {
                document.body.removeChild(iframe);
            }
        }
            //加入收藏夹
        public static addFavorite(surl: string, stitle: string) {
            try {
                window.external.addFavorite(surl, stitle);
            } catch (e) {
                try {
                    window.sidebar.addpanel(stitle, surl, "");
                } catch (e) {
                    alert("加入收藏失败,请使用ctrl+d进行添加");
                }
            }
        }
            //设为首页
        public static setHome(obj, vrl) {
            try {
                obj.style.behavior = 'url(#default#homepage)';
                obj.sethomepage(vrl);
            } catch (e) {
                if (window.netscape) {
                    try {
                        netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                    } catch (e) {
                        alert("此操作被浏览器拒绝!\n请在浏览器地址栏输入'about:config'并回车\n然后将[signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
                    }
                } else {
                    alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动设置为首页。");
                }
            }
        }
    }

    export class Browser {
        
    }
}