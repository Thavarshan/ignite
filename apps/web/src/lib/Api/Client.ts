import ClientContract from '@/lib/Contracts/Api/Client';
import ErrorResponse from '../Contracts/Http/Responses/ErrorResponse';
import { apiUrl } from '../Utils/url';
import Form from '@ignite/formlink';
import axios from '../Utils/axios';
import { AxiosStatic } from 'axios';
import ErrorParser from '../Http/ErrorParser';
import _ from 'lodash';

export default class Client implements ClientContract {
    protected resource = '';

    protected token?: string;

    protected errors?: ErrorResponse | ErrorResponse[];

    constructor (token: string) {
        this.setToken(token);
    }

    public resources (name: string): Client {
        this.resource = name;

        return this;
    }

    public async get (id: string): Promise<any> {
        const http = this.getHttp();

        const data = await http.get(this.makeUrl(id), {
            onSuccess: ({ data }: any) => data
        });

        return data;
    }

    public async all (): Promise<any[]> {
        const http = this.getHttp();

        const data = await http.get(this.makeUrl(), {
            onSuccess: ({ data }: any) => data.data,
            onFail: (error: any) => {
                let e: any = ErrorParser.parse(error.response.data);

                if (_.isArray(e)) {
                    e = _.first(e);
                }

                throw new Error(e.message as string);
            }
        });

        return data;
    }

    public async create (data: { [key: string]: any; }): Promise<any> {
        const http = this.getHttp(data);

        http.post(this.makeUrl());
    }

    protected getHttp (data: { [key: string]: any; } = {}): Form {
        const http = Form.create(data, { token: this.token });

        http.setHttpHandlerInstance(axios as AxiosStatic);

        return http;
    }

    protected makeUrl (url?: string): string {
        url = url ? `${this.resource}/${url}` : this.resource;

        return apiUrl(url).toString();
    }

    public getErrors (): undefined | ErrorResponse | ErrorResponse[] {
        return this.errors;
    }

    public setToken (token: string): void {
        this.token = token;
    }
}
