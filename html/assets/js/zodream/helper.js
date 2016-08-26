var Helper;
(function (Helper) {
    var StringExpand = (function () {
        function StringExpand() {
        }
        StringExpand.pad = function (arg, length) {
            var argString = arg.toString();
            var len = argString.length;
            while (len < length) {
                argString = "0" + argString;
                len++;
            }
            return argString;
        };
        return StringExpand;
    }());
    Helper.StringExpand = StringExpand;
    var Random = (function () {
        function Random() {
        }
        Random.getNumber = function (min, max) {
            max++;
            return Math.floor(Math.random() * (max - min)) + min;
        };
        Random.getNumberByLength = function (length) {
            return StringExpand.pad(Math.floor(Math.random() * Math.pow(10, length)), length);
        };
        Random.getWord = function (length) {
            var map = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            var arg = "", len = 0;
            while (length > len) {
                arg += map[this.getNumber(0, 62)];
                len++;
            }
            return arg;
        };
        Random.getGuid = function () {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        };
        return Random;
    }());
    Helper.Random = Random;
    var Request = (function () {
        function Request() {
        }
        Request.queryString = function () {
            var s1;
            var q = {};
            var s = document.location.search.substring(1).split("&");
            for (var i = 0, l = s.length; i < l; i++) {
                s1 = s[i].split("=");
                if (s1.length > 1) {
                    var t = s1[1].replace(/\+/g, " ");
                    try {
                        q[s1[0]] = decodeURIComponent(t);
                    }
                    catch (e) {
                        q[s1[0]] = decodeURI(t);
                    }
                }
            }
            return q;
        };
        Request.url = function () {
            return window.location.href;
        };
        Request.urlEncode = function (str) {
            if (str == null)
                return "";
            return str.replace(/\+/g, encodeURI("%2B"));
        };
        Request.domain = function () {
            return window.location.host;
        };
        Request.back = function () {
            window.history.go(-1);
        };
        Request.print = function (id) {
            var el = document.getElementById(id);
            var iframe = document.createElement('iframe');
            var doc = null;
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
        };
        Request.addFavorite = function (surl, stitle) {
            try {
                window.external.addFavorite(surl, stitle);
            }
            catch (e) {
                try {
                    window.sidebar.addpanel(stitle, surl, "");
                }
                catch (e) {
                    alert("加入收藏失败,请使用ctrl+d进行添加");
                }
            }
        };
        Request.setHome = function (obj, vrl) {
            try {
                obj.style.behavior = 'url(#default#homepage)';
                obj.sethomepage(vrl);
            }
            catch (e) {
                if (window.netscape) {
                    try {
                        netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                    }
                    catch (e) {
                        alert("此操作被浏览器拒绝!\n请在浏览器地址栏输入'about:config'并回车\n然后将[signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
                    }
                }
                else {
                    alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动设置为首页。");
                }
            }
        };
        return Request;
    }());
    Helper.Request = Request;
    var Browser = (function () {
        function Browser() {
        }
        return Browser;
    }());
    Helper.Browser = Browser;
})(Helper || (Helper = {}));
//# sourceMappingURL=helper.js.map