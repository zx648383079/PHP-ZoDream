interface INode {
    attr?: {[tag: string]: string|any},
    child?: INode[];
    node: string;
    tag?: string;
    text?: string;
}

(function (global) {

    // Regular Expressions for parsing tags and attributes
    const startTag = /^<([-A-Za-z0-9_]+)((?:\s+[a-zA-Z_:][-a-zA-Z0-9_:.]*(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/,
        endTag = /^<\/([-A-Za-z0-9_]+)[^>]*>/,
        attr = /([a-zA-Z_:][-a-zA-Z0-9_:.]*)(?:\s*=\s*(?:(?:"((?:\\.|[^"])*)")|(?:'((?:\\.|[^'])*)')|([^>\s]+)))?/g;

    // Empty Elements - HTML 5
    const empty = makeMap("area,base,basefont,br,col,frame,hr,img,input,link,meta,param,embed,command,keygen,source,track,wbr");

    // Block Elements - HTML 5
    const block = makeMap("a,address,article,applet,aside,audio,blockquote,button,canvas,center,dd,del,dir,div,dl,dt,fieldset,figcaption,figure,footer,form,frameset,h1,h2,h3,h4,h5,h6,header,hgroup,hr,iframe,ins,isindex,li,map,menu,noframes,noscript,object,ol,output,p,pre,section,script,table,tbody,td,tfoot,th,thead,tr,ul,video");

    // Inline Elements - HTML 5
    const inline = makeMap("abbr,acronym,applet,b,basefont,bdo,big,br,button,cite,code,del,dfn,em,font,i,iframe,img,input,ins,kbd,label,map,object,q,s,samp,script,select,small,span,strike,strong,sub,sup,textarea,tt,u,var");

    // Elements that you can, intentionally, leave open
    // (and which close themselves)
    const closeSelf = makeMap("colgroup,dd,dt,li,options,p,td,tfoot,th,thead,tr");

    // Attributes that have their values filled in disabled="disabled"
    const fillAttrs = makeMap("checked,compact,declare,defer,disabled,ismap,multiple,nohref,noresize,noshade,nowrap,readonly,selected");

    // Special Elements (can contain anything)
    const special = makeMap("script,style");

    global.HTMLParser = function (html: string, handler) {
        let index, chars, match, stack = [],
            last = html;

        while (html) {
            chars = true;

            // Make sure we're not in a script or style element
            if (!stack[stack.length - 1] || !special[stack[stack.length - 1]]) {

                // Comment
                if (html.indexOf("<!--") == 0) {
                    index = html.indexOf("-->");

                    if (index >= 0) {
                        if (handler.comment)
                            handler.comment(html.substring(4, index));
                        html = html.substring(index + 3);
                        chars = false;
                    }

                    // end tag
                } else if (html.indexOf("</") == 0) {
                    match = html.match(endTag);

                    if (match) {
                        html = html.substring(match[0].length);
                        match[0].replace(endTag, parseEndTag);
                        chars = false;
                    }

                    // start tag
                } else if (html.indexOf("<") == 0) {
                    match = html.match(startTag);

                    if (match) {
                        html = html.substring(match[0].length);
                        match[0].replace(startTag, parseStartTag);
                        chars = false;
                    }
                }

                if (chars) {
                    index = html.indexOf("<");

                    let text = index < 0 ? html : html.substring(0, index);
                    html = index < 0 ? "" : html.substring(index);

                    if (handler.chars)
                        handler.chars(text);
                }

            } else {
                html = html.replace(new RegExp("([\\s\\S]*?)<\/" + stack[stack.length - 1] + "[^>]*>"), function (all, text) {
                    text = text.replace(/<!--([\s\S]*?)-->|<!\[CDATA\[([\s\S]*?)]]>/g, "$1$2");
                    if (handler.chars)
                        handler.chars(text);

                    return "";
                });

                parseEndTag("", stack[stack.length - 1]);
            }

            if (html == last)
                throw "Parse Error: " + html;
            last = html;
        }

        // Clean up any remaining tags
        parseEndTag();

        function parseStartTag(tag, tagName, rest, unary) {
            tagName = tagName.toLowerCase();

            if (block[tagName]) {
                while (stack[stack.length - 1] && inline[stack[stack.length - 1]]) {
                    parseEndTag("", stack[stack.length - 1]);
                }
            }

            if (closeSelf[tagName] && stack[stack.length - 1] == tagName) {
                parseEndTag("", tagName);
            }

            unary = empty[tagName] || !!unary;

            if (!unary)
                stack.push(tagName);

            if (handler.start) {
                let attrs = [];

                rest.replace(attr, function (match, name) {
                    let value = arguments[2] ? arguments[2] :
                        arguments[3] ? arguments[3] :
                        arguments[4] ? arguments[4] :
                        fillAttrs[name] ? name : "";

                    attrs.push({
                        name: name,
                        value: value,
                        escaped: value.replace(/(^|[^\\])"/g, '$1\\\"') //"
                    });
                });

                if (handler.start)
                    handler.start(tagName, attrs, unary);
            }
        }

        function parseEndTag(tag ? : string, tagName ? : string) {
            // If no tag name is provided, clean shop
            if (!tagName)
                var pos = 0;

            // Find the closest opened tag of the same type
            else
                for (var pos = stack.length - 1; pos >= 0; pos--)
                    if (stack[pos] == tagName)
                        break;

            if (pos >= 0) {
                // Close all the open elements, up the stack
                for (let i = stack.length - 1; i >= pos; i--)
                    if (handler.end)
                        handler.end(stack[i]);

                // Remove the open elements from the stack
                stack.length = pos;
            }
        }
    };

    global.HTMLtoXML = function (html) {
        let results = "";

        global.HTMLParser(html, {
            start: function (tag, attrs, unary) {
                results += "<" + tag;

                for (let i = 0; i < attrs.length; i++)
                    results += " " + attrs[i].name + '="' + attrs[i].escaped + '"';
                results += ">";
            },
            end: function (tag) {
                results += "</" + tag + ">";
            },
            chars: function (text) {
                results += text;
            },
            comment: function (text) {
                results += "<!--" + text + "-->";
            }
        });

        return results;
    };

    global.HTMLtoDOM = function (html, doc) {
        // There can be only one of these elements
        let one = makeMap("html,head,body,title");

        // Enforce a structure for the document
        let structure = {
            link: "head",
            base: "head"
        };

        if (!doc) {
            if (typeof DOMDocument != "undefined")
                doc = new DOMDocument();
            else if (typeof document != "undefined" && document.implementation && document.implementation.createDocument)
                doc = document.implementation.createDocument("", "", null);
            else if (typeof ActiveX != "undefined")
                doc = new ActiveXObject("Msxml.DOMDocument");

        } else
            doc = doc.ownerDocument ||
            doc.getOwnerDocument && doc.getOwnerDocument() ||
            doc;

        let elems = [],
            documentElement = doc.documentElement ||
            doc.getDocumentElement && doc.getDocumentElement();

        // If we're dealing with an empty document then we
        // need to pre-populate it with the HTML document structure
        if (!documentElement && doc.createElement)(function () {
            let html = doc.createElement("html");
            let head = doc.createElement("head");
            head.appendChild(doc.createElement("title"));
            html.appendChild(head);
            html.appendChild(doc.createElement("body"));
            doc.appendChild(html);
        })();

        // Find all the unique elements
        if (doc.getElementsByTagName)
            for (let i in one)
                one[i] = doc.getElementsByTagName(i)[0];

        // If we're working with a document, inject contents into
        // the body element
        let curParentNode = one.body;

        global.HTMLParser(html, {
            start: function (tagName, attrs, unary) {
                // If it's a pre-built element, then we can ignore
                // its construction
                if (one[tagName]) {
                    curParentNode = one[tagName];
                    if (!unary) {
                        elems.push(curParentNode);
                    }
                    return;
                }

                let elem = doc.createElement(tagName);

                for (let attr in attrs)
                    elem.setAttribute(attrs[attr].name, attrs[attr].value);

                if (structure[tagName] && typeof one[structure[tagName]] != "boolean")
                    one[structure[tagName]].appendChild(elem);

                else if (curParentNode && curParentNode.appendChild)
                    curParentNode.appendChild(elem);

                if (!unary) {
                    elems.push(elem);
                    curParentNode = elem;
                }
            },
            end: function (tag) {
                elems.length -= 1;

                // Init the new parentNode
                curParentNode = elems[elems.length - 1];
            },
            chars: function (text) {
                curParentNode.appendChild(doc.createTextNode(text));
            },
            comment: function (text) {
                // create comment node
            }
        });

        return doc;
    };

    function makeMap(str) {
        let obj = {},
            items = str.split(",");
        for (let i = 0; i < items.length; i++)
            obj[items[i]] = true;
        return obj;
    }


    function q(v) {
        return '"' + v + '"';
    }

    function removeDOCTYPE(html) {
        return html
            .replace(/<\?xml.*\?>\n/, '')
            .replace(/<!doctype.*\>\n/, '')
            .replace(/<!DOCTYPE.*\>\n/, '');
    }

    global.HTMLtoJSON = function (html: string): INode {
        html = removeDOCTYPE(html);
        let bufArray = [];
        let results: INode = {
            node: 'root',
            child: [],
        };
        global.HTMLParser(html, {
            start: function (tag, attrs, unary) {
                // node for this element
                let node: INode = {
                    node: 'element',
                    tag: tag,
                };
                if (attrs.length !== 0) {
                    node.attr = attrs.reduce(function (pre, attr) {
                        let name = attr.name;
                        let value = attr.value;

                        // has multi attibutes
                        // make it array of attribute
                        if (value.match(/ /)) {
                            value = value.split(' ');
                        }

                        // if attr already exists
                        // merge it
                        if (pre[name]) {
                            if (Array.isArray(pre[name])) {
                                // already array, push to last
                                pre[name].push(value);
                            } else {
                                // single value, make it array
                                pre[name] = [pre[name], value];
                            }
                        } else {
                            // not exist, put it
                            pre[name] = value;
                        }

                        return pre;
                    }, {});
                }
                if (unary) {
                    // if this tag dosen't have end tag
                    // like <img src="hoge.png"/>
                    // add to parents
                    let parent = bufArray[0] || results;
                    if (parent.child === undefined) {
                        parent.child = [];
                    }
                    parent.child.push(node);
                } else {
                    bufArray.unshift(node);
                }
            },
            end: function (tag) {
                // merge into parent tag
                let node = bufArray.shift();
                if (node.tag !== tag) console.error('invalid state: mismatch end tag');

                if (bufArray.length === 0) {
                    results.child.push(node);
                } else {
                    let parent = bufArray[0];
                    if (parent.child === undefined) {
                        parent.child = [];
                    }
                    parent.child.push(node);
                }
            },
            chars: function (text) {
                let node = {
                    node: 'text',
                    text: text,
                };
                if (bufArray.length === 0) {
                    results.child.push(node);
                } else {
                    let parent = bufArray[0];
                    if (parent.child === undefined) {
                        parent.child = [];
                    }
                    parent.child.push(node);
                }
            },
            comment: function (text) {
                let node = {
                    node: 'comment',
                    text: text,
                };
                let parent = bufArray[0];
                if (parent.child === undefined) {
                    parent.child = [];
                }
                parent.child.push(node);
            },
        });
        return results;
    };

    global.JSONtoHTML = function(json: INode): string {
        // Empty Elements - HTML 4.01
        let empty = ['area', 'base', 'basefont', 'br', 'col', 'frame', 'hr', 'img', 'input', 'isindex', 'link', 'meta', 'param', 'embed'];

        let child = '';
        if (json.child) {
            child = json.child.map(function (c) {
                return global.JSONtoHTML(c);
            }).join('');
        }

        let attr = '';
        if (json.attr) {
            attr = Object.keys(json.attr).map(function (key) {
                let value = json.attr[key];
                if (Array.isArray(value)) value = value.join(' ');
                return key + '=' + q(value);
            }).join(' ');
            if (attr !== '') attr = ' ' + attr;
        }

        if (json.node === 'element') {
            let tag = json.tag;
            if (empty.indexOf(tag) > -1) {
                // empty element
                return '<' + json.tag + attr + '/>';
            }

            // non empty element
            let open = '<' + json.tag + attr + '>';
            let close = '</' + json.tag + '>';
            return open + child + close;
        }

        if (json.node === 'text') {
            return json.text;
        }

        if (json.node === 'comment') {
            return '<!--' + json.text + '-->';
        }

        if (json.node === 'root') {
            return child;
        }
    };

    global.JSONtoWXML = function(json: INode) {
        if (json.node == 'text') {
            if (/^\s+$/.test(json.text)) {
                return '';
            }
            return `<text>${json.text}</text>`;
        }

        let child = '';
        if (json.child) {
            child = json.child.map(function (c) {
                return global.JSONtoWXML(c);
            }).join('');
        }

        const default_allow_attrs = ['id', 'class', 'style', 'src'],
            input_allow_attrs = ['value', 'type', 'password', 'placeholder', 'disabled', 'maxlength', 'focus', 'cursor', 'class', 'id'];

        if (json.node === 'root') {
            return child;
        }

        if (json.node === 'comment') {
            return '<!--' + json.text + '-->';
        }

        if (json.node != 'element') {
            return child;
        }
        if(json.tag == 'img') {
            const attr = parseNodeAttr(json.attr);
            return `<image${attr}></image>`
        }
        if (json.tag == 'input') {
            return parseInput(json);
        }
        if (json.tag == 'button') {
            return parseButton(json);
        }
        if (json.tag == 'form') {
            const attr = parseNodeAttr(json.attr);
            return `<form${attr}>${child}</form>`;
        }
        if (json.child && json.child.length == 1 && json.child[0].node == 'text') {
            child = json.child[0].text;
        }
        if (json.tag == 'label') {
            const attr = parseNodeAttr(json.attr, ['for']);
            return `<label${attr}>${child}</label>`;
        }
        if (json.tag == 'textarea') {
            json.attr.vlaue = child;
            const attr = parseNodeAttr(json.attr, input_allow_attrs);
            return `<textarea${attr}/>`;
        }
        if (json.tag == 'a') {
            let attr = parseNodeAttr(json.attr, ['id', 'class']);
            if (json.attr && json.attr.href) {
                attr += ' url='+q(json.attr.href);
            }
            return `<navigator${attr}>${child}</navigator>`;
        }
        const attr = parseNodeAttr(json.attr);
        if (['i', 'span', 'strong', 'block', 'font'].indexOf(json.tag) >= 0 
        && (!json.child || (json.child.length == 1 && json.child[0].node == 'text'))) {
            return `<text${attr}>${child}</text>`;
        }
        return `<view${attr}>${child}</view>`;

        function parseNodeAttr(attrs?: any, allows: string[] = default_allow_attrs): string {
            let str = '';
            if (!attrs) {
                return str;
            }
            for (const key in attrs) {
                if (attrs.hasOwnProperty(key) && allows.indexOf(key) >= 0) {
                    let value = attrs[key];
                    if (Array.isArray(value)) value = value.join(' ');
                    str += ' ' + key + '=' + q(value);
                }
            }
            return str;
        }

        function parseButton(node: INode): string {
            let attr = parseNodeAttr(node.attr);
            if (node.attr && ['reset', 'submit'].indexOf(node.attr.type) >= 0) {
                attr += ' form-type='+ q(node.attr.type);
            }
            return; `<button type="default"${attr}>${node.text}</button>`;
        }

        function parseInput(node: INode) {
            if (!node.attr) {
                node.attr = {
                    type:'text'
                };
            }
            if (node.attr.type == 'password') {
                node.attr.type = 'text';
                node.attr.password = 'true';
            }
            if (['button', 'reset', 'submit'].indexOf(node.attr.type) >= 0) {
                node.text = node.attr.value;
                node.tag = 'button';
                return parseButton(node);
            }
            if (node.attr.type == 'checkbox') {
                const attr = parseNodeAttr(node.attr, ['value', 'checked', 'class', 'id']);
                return `<checkbox${attr}/>`
            }
            if (node.attr.type == 'radio') {
                const attr = parseNodeAttr(node.attr, ['value', 'checked', 'class', 'id']);
                return `<radio${attr}/>`
            }
            if (['text', 'number', 'idcard', 'digit'].indexOf(node.attr.type) < 0) {
                node.attr.type = 'text';
            }
            const attr = parseNodeAttr(node.attr, input_allow_attrs);
            return `<input${attr}/>`;
        }
    };

    global.HTMLtoWXML = function(input?: string) {
        if (!input) {
            console.log('please input html content!!!');
            return 0;
        }
        const json = global.HTMLtoJSON(input);
        return global.JSONtoWXML(json);
    }
})(this);