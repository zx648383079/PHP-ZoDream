/// <reference types="jquery" />

declare class DateTimer {
    constructor(element?: JQuery, option?: any);

    init(val?: any);
    open();
    on(event: string, cb: Function);

    val(): string;
}
