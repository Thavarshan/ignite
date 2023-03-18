import { FormikHelpers } from 'formik';
import LoginFormValues from '../Contracts/Form/LoginFormValues';
import Router from 'next/router';
import Form from './Form';

export default class Login extends Form {
    protected values: LoginFormValues = {
        email: '',
        password: ''
    };

    public async handleSubmit (
        values: LoginFormValues,
        helpers: FormikHelpers<LoginFormValues>
    ): Promise<any> {
        // Login action.

        return Router.push('/auth/login');
    }
}
