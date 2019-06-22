declare const websocketStringMessageType: number;
declare const websocketIntMessageType: number;
declare const websocketBoolMessageType: number;
declare const websocketJSONMessageType: number;
declare const websocketMessagePrefix: string;
declare const websocketMessageSeparator: string;
declare const websocketMessagePrefixLen: number;
declare const websocketMessageSeparatorLen: number;
declare const websocketMessagePrefixAndSepIdx: number;
declare const websocketMessagePrefixIdx: number;
declare const websocketMessageSeparatorIdx: number;

declare class Ws {
    constructor(endpoint: string, protocols?: string|string[]);

    isNumber(obj: any): boolean
    
    isString(obj: any): boolean

    isBoolean(obj: any): boolean

    isJSON(obj: any): boolean

    _msg(event: string, websocketMessageType: string, dataMessage: string): string

    encodeMessage(event: string, data: any): string

    decodeMessage(event: string, websocketMessage: string): any

    /**
     * 根据消息内容获取自定义事件名
     * @param websocketMessage 
     */
    getWebsocketCustomEvent(websocketMessage: string): string

    /**
     * 获取自定义消息内容
     * @param event 
     * @param websocketMessage 
     */
    getCustomMessage(event: string, websocketMessage: string): string

    /**
     * 通过此方法接受消息并处理
     * @param evt 
     */
    messageReceivedFromConn(evt: {data: string})

    /**
     * 
     */
    OnConnect(fn: () => void)
    /**
     * 触发开始连接事件
     */
    fireConnect()
    /**
     * 监听断开事件
     * @param fn 
     */
    OnDisconnect(fn: () => void)
    /**
     * 触发断开事件
     */
    fireDisconnect()
    /**
     * 监听原生消息，未处理
     * @param fn 
     */
    OnMessage(fn: (msg: string) => void)
    /**
     * 触发原生消息
     * @param websocketMessage 
     */
    fireNativeMessage(websocketMessage: string)
    /**
     * 监听事件
     * @param event 
     * @param cb 
     */
    On(event: string, cb: (msg?: any) => void)
    /**
     * 手动触发消息
     * @param event 自定义事件
     * @param message 自定义数据格式
     */
    fireMessage(event: string, message: any)

    /**
     * 断开
     */
    Disconnect()

    /**
     * 发送原生消息
     * @param websocketMessage 
     */
    EmitMessage(websocketMessage: any)
    /**
     * 发送某个事件的消息 （将事件编入消息文字中）
     * @param event 
     * @param data 支持数值、布尔、JSON、string
     */
    Emit(event: string, data: any)
}