const EditorEventGetWeights = 'editor_get_wegihts';
const EditorEventGetWeightProperty = 'editor_get_wegiht_property';
const EditorEventSaveWeightProperty = 'editor_save_wegiht_property';
const EditorEventRefreshWeight = 'editor_refresh_weight';
const EditorEventAddWeight = 'editor_add_weight';
const EditorEventMoveWeight = 'editor_move_weight';
const EditorEventWeightForm = 'editor_weight_form';
const EditorEventRemoveWeight = 'editor_remove_weight';
const EditorEventSavePage = 'editor_save_page';
const EditorEventGetPage = 'editor_get_page';
const EditorEventPageList = 'editor_get_page_list';
const EditorEventWeightTree = 'editor_get_page_weight';
const EditorEventPageSetting = 'editor_get_page_setting';
const EditorEventSavePageSetting = 'editor_save_page_setting';

const EditorEventWindowResize = 'editor_Window_resize';
const EditorEventResize = 'editor_resize';
const EditorEventOuterWidthChange = 'editor_outer_width_change'; // 外部
const EditorEventViewInit = 'editor_view_init'; // 只能获取所需的元素，请不要获取其他尺寸信息
const EditorEventGetStyleSuccess = 'editor_get_style_success';
const EditorEventAfterViewInit = 'editor_after_view_init';
const EditorEventBrowserResize = 'editor_browser_resize';
const EditorEventScroll = 'editor_scroll';
const EditorEventPositionChange = 'editor_position_change';
const EditorEventDragStart = 'editor_drag_start';
const EditorEventBrowserReady = 'editor_browser_ready';
const EditorEventOpenEditDialog = 'editor_open_edit_dialog';
const EditorEventOpenProperty = 'editor_open_property';
const EditorEventDrag = 'editor_drag'; // 拖拽开始
const EditorEventDrog = 'editor_drog'; // 拖拽放下
const EditorEventOperateWeight = 'editor_operate_weight';
const EditorEventMouseMove = 'editor_mouse_move';
const EditorEventMouseUp = 'editor_mouse_up';
const EditorEventTimeLoop = 'editor_time_loop';
const EditorMobileStyle = 'mobile-style';
const EditorEventInputChange = 'value:change';
const EditorEventInputReset = 'value:reset';
const EditorEventTabToggle = 'tab:toggle';
const EditorEventListEdit = 'list:edit';
const EditorEventFlipToggle = 'flip:toggle';
const EditorEventFlipFinish = 'flip:finish';

type FailureCallbackFunc = (message: string, code?: number) => void;

interface EditorListeners {
    [EditorEventGetWeights]: (success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventGetPage]: (success: (data: IPageModel) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventPageList]: (success: (data: IPageModel[]) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventWeightTree]: (success: (data: IPageWeight[]) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventPageSetting]: (success: (data: {theme: IVisualInput[], page: IVisualInput[]}) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventSavePageSetting]: (data: any, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventWindowResize]: (width: number, height: number) => void;
    [EditorEventResize]: (width: number, height: number) => void;
    [EditorEventSavePage]: (data: any[], success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventMouseMove]: (p: IPoint) => void;
    [EditorEventMouseUp]: (p: IPoint) => void;
    [EditorEventTimeLoop]: (now: Date) => void;
    [EditorEventOuterWidthChange]: (width: number) => void;
    [EditorEventViewInit]: () => void;
    [EditorEventAfterViewInit]: () => void;
    [EditorEventDrog]: (...items: JQuery<HTMLDivElement>[]) => void;
    [EditorEventOperateWeight]: (isNew: boolean, target: JQuery<HTMLDivElement>, row: JQuery<HTMLDivElement>, replace?: JQuery<HTMLDivElement>) => void;
    [EditorEventGetWeightProperty]: (id: number, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventGetStyleSuccess]: (data: any) => void;
    [EditorEventSaveWeightProperty]: (id: number, data: Object, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventDrag]: (ele: JQuery<HTMLDivElement>, isNew: boolean, p: IPoint) => void;
    [EditorEventDragStart]: (ele: JQuery<HTMLDivElement>) => void;
    [EditorEventOpenProperty]: (target: EditorWeight) => void;
    [EditorEventWeightForm]: (id: number, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventPositionChange]: (left: number, top: number, scale: number) => void;
    [EditorEventBrowserReady]: (doc: JQuery<Document>) => void;
    [EditorEventBrowserResize]: (left: number, top: number, width: number, height: number, maxWidth: number, maxHeight: number) => void;
    [EditorEventRefreshWeight]: (id: number, success: (data: any) => void, failure: FailureCallbackFunc) => void;
    [EditorEventOpenEditDialog]: (target: EditorWeight) => void;
    [EditorEventRemoveWeight]: (id: number, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventMoveWeight]: (data: any, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventAddWeight]: (data: any, success: (data: any) => void, failure?: FailureCallbackFunc) => void;
    [EditorEventScroll]: (offset: number, horizontal: boolean) => void;
}

interface IRuleLine {
    value: number;
    label: string|number;
    horizontal?: boolean;
}


interface IPoint {
    x: number;
    y: number;
}

interface ISize {
    width: number;
    height: number;
}

interface IBound extends ISize {
    left: number;
    top: number;
}

interface IEditorPanel {
    render(): JQuery;
    show(): void;
    hide(): void; 
}

interface IPageModel {
    id: string;
    name: string;
    title: string;
    edit_url: string;
    editable_url: string;
    preview_url: string;
}


interface IPageWeight {
    id: string;
    title: string;
    children: IPageWeight[];
}

interface IItem {
    name: string;
    value: any;
}

interface IRgbaColor {
    r: number; // 255
    g: number; // 255
    b: number; // 255
    a: number; // 1
}

interface IHslColor {
    h: number; // 360
    s: number; // 100
    l: number; // 100
    a: number; // 1
}

interface IVisualInput {
    [key: string]: any;
    type: string;
    name: string;
    label: string;
    value?: any;
    items?: any[];
    input?: IVisualInput[];
    class?: string;
    hidden?: boolean;
    option?: {
        [key: string]: any;
        'data-tab': string;
        tab?: string;
    };
}

interface IEditorInputGroup {
    notify(control: IEditorElement): void;
}

interface IEditorElement {
    render(): string;
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void;
}

interface IEditorInput extends IEditorElement {
    shimmed?: string;
    label?: string;
    name?: string;
    value?: any;
    tooltip?: string;
    get isUpdated(): boolean;
    render(): string;
    ready(box: JQuery<HTMLElement>, manager: IEditorInputGroup): void;
    reset(): void;
}