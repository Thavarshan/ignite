import { AxiosInstance, AxiosStatic } from 'axios';
export type StaticThis<T> = {
    new (): T;
};
export default class HttpHandler {
    /**
     * HTTP handler configurations.
     *
     * @var {object}
     */
    config: {
        [key: string]: any;
    };
    /**
     * The HTTP request headers.
     *
     * @var {object}
     */
    headers: {
        [key: string]: any;
    };
    /**
     * The Axios client instance.
     *
     * @var {AxiosStatic}
     */
    protected client?: AxiosStatic;
    /**
     * Create new HTTP handler instance.
     *
     * @param   {AxiosStatic}  client
     *
     * @return  {void}
     */
    constructor(client?: AxiosStatic);
    /**
     * Create new HTTP handler instance.
     *
     * @param   {object}  config
     *
     * @return  {HttpHandler}
     */
    createInstance(config?: object): AxiosInstance;
    /**
     * Set HTTP handler configurations.
     *
     * @param   {object}  config
     *
     * @return  {HttpHandler}
     */
    withOptions(config: object): HttpHandler;
    /**
     * Set headers to accept JSON response.
     *
     * @return  {HttpHandler}
     */
    acceptJson(): HttpHandler;
    /**
     * Set headers to accept JSON content type response.
     *
     * @param   {string}       type
     *
     * @return  {HttpHandler}
     */
    contentType(type?: string): HttpHandler;
    /**
     * Set default base urls.
     *
     * @param   {string}       url
     *
     * @return  {HttpHandler}
     */
    baseUrl(url: string): HttpHandler;
    /**
     * Set authentication token.
     *
     * @param   {string}       token
     *
     * @return  {HttpHandler}
     */
    setToken(token?: string): HttpHandler;
    /**
     * Set request handler to accept credentials.
     *
     * @return  {HttpHandler}
     */
    withCredentials(): HttpHandler;
}
//# sourceMappingURL=HttpHandler.d.ts.map