import Axios, { AxiosInstance } from 'axios';
import { config } from '@ignite/scribe';
import _ from 'lodash';

export default class Client {
    protected config = {
        baseURL: config('apiUrl'),
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        withCredentials: true
    };

    public make (config: { [key: string]: any; } = {}): AxiosInstance {
        return Axios.create(_.merge(this.config, config));
    }
}
