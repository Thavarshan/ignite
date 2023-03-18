import Axios, { AxiosInstance } from 'axios';
import { config as appConfig } from '@ignite/scribe';
import _ from 'lodash';

export default class Client {
    public static make (config: { [key: string]: any; } = {}): AxiosInstance {
        return Axios.create(_.merge({
            baseURL: appConfig('apiUrl'),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            withCredentials: true
        }, config));
    }
}
