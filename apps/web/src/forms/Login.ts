import LoginValues from '@/contracts/forms/Login';
import Router from 'next/router';
import Form from './Form';
import { FormikHelpers } from 'formik';
import { signIn } from 'next-auth/react';

export default class Login extends Form {
    /**
     * Indicate user login process status.
     *
     * @var {boolean}
     */
    public failed = false;

    /**
     * Initital form input values.
     *
     * @var {object}
     */
    protected initialValues: LoginValues = {
        email: '',
        password: ''
    };

    /**
     * Get the initial values of the form inputs,.
     *
     * @return  {LoginValues}
     */
    public getInitialValues (): LoginValues {
        return this.initialValues;
    }

    /**
     * The action to perform on form submit event.
     *
     * @return  {Promise}
     */
    public onSubmit (): (
        _values: { [key: string]: any; },
        _helpers: FormikHelpers<LoginValues>
    ) => Promise<void> {
        return async (
            values: { [key: string]: any; },
            helpers: FormikHelpers<LoginValues>
        ) => {
            const response = await signIn('credentials', {
                email: values.email,
                password: values.password,
                redirect: false,
                callbackUrl: '/'
            });

            helpers.setSubmitting(false);

            if (response?.error) {
                this.failed = true;
            }

            helpers.resetForm();

            Router.push('/users/profile');
        };
    }
}
