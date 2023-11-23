class SwicthInput extends HTMLInputElement {

    static get observedAttributes() {
        return ['value'];
    }

    constructor() {
        super();
        const root = this.attachShadow({mode: 'open'});

        root.appendChild(document.createElement('a'));
    }

    connectedCallback() {

    }

    attributeChangedCallback(name: string, oldValue: any, newValue: any) {

    }
}

/**
 * 注册自定义控件
 */
function registerComponents(prefix = 'zre-') {
    const components: {
        [key: string]: CustomElementConstructor
    } = {
        switch: SwicthInput
    };
    for (const key in components) {
        if (Object.prototype.hasOwnProperty.call(components, key)) {
            customElements.define(prefix + key, components[key]);
        }
    }
}

registerComponents();