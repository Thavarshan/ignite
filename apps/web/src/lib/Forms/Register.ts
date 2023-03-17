import { FormikHelpers } from 'formik';
import RegisterFormValues from '../Contracts/Form/RegisterFormValues';
import Router from 'next/router';
import Form from './Form';

export default class Register extends Form {
    protected values: RegisterFormValues = {
        name: '',
        email: '',
        password: '',
        passwordConfirmation: '',
        phone: ''
    };

    public async handleSubmit (
        values: RegisterFormValues,
        helpers: FormikHelpers<RegisterFormValues>
    ): Promise<any> {
        await this.client().resources('register').create(values);

        helpers.setSubmitting(false);

        return Router.push('/auth/login');
    }
}
