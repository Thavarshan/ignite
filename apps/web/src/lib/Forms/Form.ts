/* eslint-disable no-unused-vars */
import { getCookie } from 'cookies-next';
import { FormikHelpers } from 'formik';
import Client from '../Api/Client';
import FormValues from '../Contracts/Form/FormValues';
import Router from 'next/router';

export default abstract class Form {
    protected abstract values: FormValues;

    public abstract handleSubmit (
        values: FormValues,
        helpers: FormikHelpers<FormValues>
    ): Promise<any>;

    protected client (): Client | any {
        const token = this.token();

        if (!token) {
            return Router.push('/auth/login');
        }

        return new Client(token);
    }

    protected token (): string | null {
        return getCookie('token') as string;
    }
}
