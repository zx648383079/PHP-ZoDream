export class Upload {
    constructor() {
        
    }

    public element: HTMLInputElement;

    public mode: UploadMode = UploadMode.Single;

    public changeCallback: EventListener;

    public createElement() {
        this.element = document.createElement("input");
        this.element.type = "file";
        this.element.className = "_uploadFile";
        if (this.mode == UploadMode.Multiple) {
            this.element.multiple = true;
        }
        document.body.appendChild(this.element);
        if (this.changeCallback) {
            this.addEventListener(this.changeCallback);
        }
    }

    public addEventListener(callback: EventListener, event: string = 'change') {
        if (!this.element) {
            this.createElement();
        }
        this.element.addEventListener(event, callback);
    }
}

export enum UploadMode {
    Single,
    Multiple
}