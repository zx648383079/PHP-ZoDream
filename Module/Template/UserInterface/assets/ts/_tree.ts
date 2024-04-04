class EditorTree {
    constructor(
        private box: JQuery<HTMLDivElement>,
        private itemForm: JQuery<HTMLDivElement>,
        private dataInput: JQuery<HTMLInputElement>,
        private option = {
            titleKey: 'title',
            childrenKey: 'children',
            iconKey: 'icon',
            inputPrefix: 'item_',
        }
    ) {
        const val = this.dataInput.val();
        if (val) {
            this.items = JSON.parse(val as string);
        }
        this.bindEvent();
        this.render();
    }

    private items: any[] = [];
    private editData: any;
    private parentData: any[];
    private editNode: JQuery<HTMLDivElement>;
    private parentNode: JQuery<HTMLDivElement>;

    private bindEvent() {
        const that = this;
        this.box.on('click', '.tree-add', function() {
            const parent = $(this).parent();
            that.addSource(parent);
        }).on('click', '.tree-item-line', function() {
            that.editSource($(this).closest('.tree-item'));
        }).on('click', '.tree-item-action .fa-arrow-up', function(e) {
            e.stopPropagation();
            that.move($(this).closest('.tree-item'), -1);
        }).on('click', '.tree-item-action .fa-arrow-down', function(e) {
            e.stopPropagation();
            that.move($(this).closest('.tree-item'), 1);
        }).on('click', '.tree-item-action .fa-times',  function(e) {
            e.stopPropagation();
            that.remove($(this).closest('.tree-item'));
        });
        this.itemForm.on('click', '.tree-save-btn', function() {
            that.saveSource();
        });
    }

    private getPath(item: JQuery<HTMLDivElement>): number[] {
        let parent = item.parent();
        const map = [item.index()];
        while (true) {
            if (!parent.hasClass('tree-children')) {
                break;
            }
            const ele = parent.closest('.tree-item');
            map.push(ele.index());
            parent = ele.parent();
        }
        return map.reverse();
    }

    private getSource(item: JQuery<HTMLDivElement>): [any[], number] {
        const map = this.getPath(item);
        const last = map.pop();
        let items = this.items;
        for (const i of map) {
            items = items[i][this.option.childrenKey];
        }
        return [items, last];
    }

    private remove(item: JQuery<HTMLDivElement>) {
        const [items, i] = this.getSource(item);
        items.splice(i, 1);
        if (this.editNode.is(item)) {
            this.editData = undefined;
            this.editNode = undefined;
        }
        item.remove();
        this.output();
    }

    private move(item: JQuery<HTMLDivElement>, offset: number) {
        if (offset === 0) {
            return;
        }
        const index = item.index();
        if (index == 0 && offset < 0) {
            return;
        }
        const parent = item.parent();
        const items = parent.children('.tree-item');
        if (index === items.length - 1 && offset > 0) {
            return;
        }
        if (offset < 0 && index + offset < 0) {
            offset = - index;
        } else if (offset > 0 && index + offset >= items.length) {
            offset = items.length - index - 1;
        }
        const [dataItems] = this.getSource(item);
        const target = index + offset;
        dataItems.splice(target, 0, dataItems[index]);
        dataItems.splice(index + offset < 0 ? 1 : 0, 1);
        item.insertBefore(items[target]);
        this.output();
    }

    private output() {
        this.dataInput.val(JSON.stringify(this.items));
    }

    private editSource(item: JQuery<HTMLDivElement>) {
        this.editNode = item;
        this.parentNode = item.parent();
        const [items, i] = this.getSource(item);
        this.editData = items[i];
        this.parentData = items;
        EditorHelper.eachObject(this.editData, (v, k) => {
            this.itemForm.find('*[name='+ this.option.inputPrefix +k+']').val(v).trigger('change');
        });
    }

    private addSource(parent: JQuery<HTMLDivElement>) {
        this.parentNode = parent;
        this.editData = undefined;
        this.editNode = undefined;
        this.itemForm.find('input,textarea').each(function() {
            const $this = $(this);
            const type = $this.attr('type');
            if (type === 'checkbox' || type === 'radio'
            || type === 'color' || type === 'hidden') {
                return;
            }
            $this.val('');
        });
        if (!parent.hasClass('tree-children')) {
            this.parentData = this.items;
            return;
        }
        const [items, i] = this.getSource(parent.closest('.tree-item'));
        this.parentData = items[i][this.option.childrenKey];
    }

    private saveSource() {
        if (!this.parentData) {
            this.parentData = this.items;
            this.parentNode = this.box;    
        }
        const data: any = this.editData ? this.editData : {};
        const that = this;
        this.itemForm.find('select,input,textarea').each(function() {
            const $this = $(this);
            if ($this.closest('.hidden').length > 0) {
                return;
            }
            data[$this.attr('name').substring(that.option.inputPrefix.length)] = $this.val();
        });
        if (!data[this.option.titleKey]) {
            Dialog.tip('请输入标题');
            return;
        }
        if (data.type === 'children') {
            data[this.option.childrenKey] = [];
        } else {
            delete data[this.option.childrenKey];
        }
        if (this.editNode) {
            this.editNode.replaceWith(this.renderTreeItem(data));
        } else {
            this.editData = data;
            this.parentData.push(data);
            const addNode = this.parentNode.children('.tree-add');
            addNode.before(this.renderTreeItem(data)) as any;
            this.editNode = addNode.prev() as any;
            console.log(this.editNode);
            
        }
        this.output();
    }

    private render() {
        this.box.html(this.renderTree(this.items));
    }

    private renderTree(items: any[]) {
        let html = '';
        for (const item of items) {
            html += this.renderTreeItem(item);
        }
        return html + `<div class="tree-add">
        <i class="fa fa-plus"></i>
    </div>`;
    }

    private renderTreeItem(item: any) {
        let html = '';
        if (item[this.option.childrenKey]) {
            html = '<div class="tree-children">' + this.renderTree(item[this.option.childrenKey]) + '</div>';
        }
        let icon = '';
        if (item[this.option.iconKey]) {
            icon = '<i class="fa '+ item[this.option.iconKey] +'"></i>';
        }
        const title = item[this.option.titleKey];
        return `<div class="tree-item">
        <div class="tree-item-line">
            ${icon}
            <span>${title}</span>
            <div class="tree-item-action">
                <i class="fa fa-arrow-up"></i>
                <i class="fa fa-arrow-down"></i>
                <i class="fa fa-times"></i>
            </div>
        </div>
        ${html}
    </div>`
    }
}