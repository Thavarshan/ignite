import _ from 'lodash';
import axios, { AxiosInstance, AxiosStatic } from 'axios';

export type StaticThis<T> = { new(): T; };

export default class HttpHandler {
    /**
     * HTTP handler configurations.
     *
     * @var {object}
     */
    public config: { [key: string]: any; } = {
        withCredentials: false,
        responseType: 'json',
        responseEncoding: 'utf8',
        xsrfCookieName: 'XSRF-TOKEN',
        xsrfHeaderName: 'X-XSRF-TOKEN',
        maxRedirects: 21
    };

    /**
     * The HTTP request headers.
     *
     * @var {object}
     */
    public headers: { [key: string]: any; } = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-Formlink-Header': true
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
    constructor (client?: AxiosStatic) {
        this.client = client;
    }

    /**
     * Create new HTTP handler instance.
     *
     * @param   {object}  config
     *
     * @return  {HttpHandler}
     */
    createInstance (config?: object): AxiosInstance {
        config = _.merge(
            this.config,
            { headers: this.headers },
            config || {}
        );

        return (this.client || axios).create(config);
    }

    /**
     * Set HTTP handler configurations.
     *
     * @param   {object}  config
     *
     * @return  {HttpHandler}
     */
    withOptions (config: object): HttpHandler {
        _.merge(this.config, config);

        return this;
    }

    /**
     * Set headers to accept JSON response.
     *
     * @return  {HttpHandler}
     */
    acceptJson (): HttpHandler {
        _.set(this.headers, 'Accept', 'application/json');

        return this;
    }

    /**
     * Set headers to accept JSON content type response.
     *
     * @param   {string}       type
     *
     * @return  {HttpHandler}
     */
    contentType (type?: string): HttpHandler {
        _.set(this.headers, 'Content-Type', type || 'application/json');

        return this;
    }

    /**
     * Set default base urls.
     *
     * @param   {string}       url
     *
     * @return  {HttpHandler}
     */
    baseUrl (url: string): HttpHandler {
        if (!url) {
            return this;
        }

        _.set(this.config, 'baseUrl', url);

        return this;
    }

    /**
     * Set authentication token.
     *
     * @param   {string}       token
     *
     * @return  {HttpHandler}
     */
    setToken (token?: string): HttpHandler {
        if (!token) {
            return this;
        }

        _.set(this.headers, 'Authorization', `Bearer ${token}`);

        return this;
    }

    /**
     * Set request handler to accept credentials.
     *
     * @return  {HttpHandler}
     */
    withCredentials (): HttpHandler {
        _.set(this.config, 'withCredentials', true);

        return this;
    }
}
