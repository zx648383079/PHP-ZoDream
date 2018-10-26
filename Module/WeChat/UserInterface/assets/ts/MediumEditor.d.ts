interface MediumEditorOption {
    delay?: number,
    targetBlank?: boolean,
    toolbar?: any,
    anchor?: any,
    paste?: any,
    anchorPreview?: any,
    placeholder?: any
    activeButtonClass?: string
    autoLink: boolean
    buttonLabels: boolean
    contentWindow: Window
    disableDoubleReturn: boolean
    disableEditing: boolean
    disableExtraSpaces: boolean
    disableReturn: boolean
    elementsContainer: HTMLBodyElement
    extensions: any
    ownerDocument: Document
    spellcheck: number
}

declare class MediumEditorUtil {
    insertHTMLCommand(doc: Document, html: string)
}

declare class MediumEditorEvent {

}
declare class MediumEditorSelection {
    clearSelection()
    doesRangeStartWithImages()
    exportSelection()
    findMatchingSelectionParent()
    getCaretOffsets(element: any): {left: number, right: number}
    getIndexRelativeToAdjacentEmptyBlocks()
    getSelectedElements(doc: Document): Array<HTMLElement>
    getSelectedParentElement(doc: Document)
    getSelectionElement(doc: Document): HTMLElement
    getSelectionHtml(doc: Document): string
    getSelectionRange(doc: Document): Range
    getSelectionStart()
    getTrailingImageCount()
    importSelection()
    importSelectionMoveCursorPastAnchor()
    importSelectionMoveCursorPastBlocks()
    moveCursor()
    rangeSelectsSingleNode()
    select()
    selectNode()
    selectRange()
    selectionContainsContent()
    selectionInContentEditableFalse()
}

declare class MediumEditor {

    static selection: MediumEditorSelection

    static util: MediumEditorUtil

    elements: Array<HTMLElement>

    events: MediumEditorEvent

    extensions: Array<any>

    id: number

    isActive: boolean

    options: MediumEditorOption

    origElements: string | HTMLElement | Array<any> | NodeList | HTMLCollection

    constructor(elements: string | HTMLElement | Array<any> | NodeList | HTMLCollection, options?: MediumEditorOption | any);
    /**
     * 删除编辑器
     */
    destroy()
    /**
     * 初始化该实例
     */
    setup()
    /**
     * 将一个或多个元素动态添加到已初始化的MediumEditor实例中
     * @param elements 如果作为字符串传递，则在调用中用作document.querySelectorAll()查找器以在页面上查找元素。
     */
    addElements(elements: string | HTMLElement | Array<any> | NodeList | HTMLCollection)

    /**
     * 删除一个或多个元素
     * @param elements 
     */
    removeElements(elements: string | HTMLElement | Array<any> | NodeList | HTMLCollection)
    /**
     * 通过浏览器的内置addEventListener(type, listener, useCapture)API 将事件监听器附加到特定元素。但是，此辅助方法还可确保在销毁MediumEditor时，此事件侦听器将自动与DOM分离。
     * @param targets 
     * @param event 
     * @param listener 
     * @param useCapture 
     */
    on(targets: HTMLElement | NodeList, event: string, listener: Function, useCapture: boolean)
    /**
     * 通过浏览器的内置removeEventListener(type, listener, useCapture)API 从特定元素或元素中分离事件侦听器。
     * @param targets 
     * @param event 
     * @param listener 
     * @param useCapture 
     */
    off(targets: HTMLElement | NodeList, event: string, listener: Function, useCapture: boolean)
    /**
     * 为指定的自定义事件名称附加侦听器。
     * @param name 
     * @param listener 
     */
    subscribe(name: string, listener: (event: any, editable: HTMLElement) => void)
    /**
     * 为指定的自定义事件名称分离自定义事件侦听器。
     * @param name 
     * @param listener 
     */
    unsubscribe(name: string, listener: Function)
    /**
     * 手动触发自定义事件。
     * @param name 
     * @param data 
     * @param editable 
     */
    trigger(name: string, data: any, editable: HTMLElement)
    /**
     * 如果启用了工具栏，请手动强制工具栏根据用户的当前选择进行更新。这包括隐藏/显示工具栏，定位工具栏以及更新工具栏按钮的启用/禁用状态。
     */
    checkSelection()

    /**
     * 返回所选文本的数据表示形式，可以通过它来应用importSelection(selectionState)。此数据将包括选择的开始和结束，以及选择所在的编辑器元素
     */
    exportSelection()
    /**
     * 使用先前选定文本的数据表示（即返回的值）恢复选择exportSelection()
     * @param selectionState 
     * @param favorLaterSelectionAnchor 
     */
    importSelection(selectionState: any, favorLaterSelectionAnchor: boolean)
    /**
     * 返回对当前具有焦点的编辑器元素的引用（如果编辑器具有焦点）
     */
    getFocusedElement()
    /**
     * 返回对用户选择当前所在的编辑器元素的引用。
     * @param range 
     */
    getSelectedParentElement(range)
    /**
     * 将选择恢复为上次saveSelection()调用时所选的内容。
     */
    restoreSelection()
    /**
     * 内部存储用户的当前选择。这可以通过调用恢复restoreSelection()。
     */
    saveSelection()
    /**
     * 扩展选择以包含焦点编辑器元素中的所有文本。
     */
    selectAllContents()
    /**
     * 更改用户的选择以选择提供的元素的内容并更新工具栏以反映此更改。
     * @param element 
     */
    selectElement(element: HTMLElement)
    stopSelectionUpdates()
    startSelectionUpdates()
    /**
     * 将文本转换为纯文本并将当前选择替换为结果
     * @param text 
     */
    cleanPaste(text: string)
    /**
     * 通过本机document.execCommand('createLink')命令创建链接
     * @param opts 
     */
    createLink(opts: any)
    /**
     * 通过document.execCommand执行内置操作
     * @param action 
     * @param opts 
     */
    execAction(action: string, opts?: any)
    /**
     * 用html替换当前选择
     * @param html 
     * @param options 
     */
    pasteHTML(html: string, options?: any)
    queryCommandState(action)
    /**
     * 触发编辑器以检查html的更新，并在editableInput需要时触发事件。
     * @param editable 
     */
    checkContentChanged(editable?: HTMLElement)
    /**
     * 延迟执行任何函数延迟时间作为延迟选项。
     * @param fn 
     */
    delay(fn: Function)
    /**
     * 返回第一位编辑修剪html内容元素，或元素的index
     * @param index 
     */
    getContent(index?: number)
    /**
     * 获取对具有指定名称的扩展的引用。
     * @param name 
     */
    getExtensionByName(name: string)
    /**
     * 将所有编辑器元素的内容重置为它们在添加到编辑器时的值。如果提供了特定的编辑器元素，则仅重置该元素的内容
     * @param element 
     */
    resetContent(element?: HTMLElement)
    /**
     * 返回一个JSON对象，包括编辑器中每个元素的内容
     */
    serialize()
    /**
     * 设置第一位编辑HTML内容元素，或元素的index。确保editableInput触发事件。
     * @param html 
     * @param index 
     */
    setContent(html: string, index?: number)
    /**
     * 判断焦点在最后
     */
    caretIsAtEnd(): boolean
    /**
     * 判断焦点在最前
     */
    caretIsAtStart(): boolean
    /**
     * 设置焦点在最后
     */
    setCaretAtEnd()
    /**
     * 设置焦点在最前
     */
    setCaretAtStart()
    /**
     * 插入html
     * @param html 
     */
    insertHTML(html: string)
    /**
     * 插入html块
     * @param html 
     */
    insertBlock(html: string)

    static getEditorFromElement(element)
}