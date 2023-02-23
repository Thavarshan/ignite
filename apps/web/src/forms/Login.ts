import LoginValues from '@/contratcs/forms/Login';
import Form from './Form';
import { FormikHelpers } from 'formik';
import { signIn } from 'next-auth/react';

export default class Login extends Form {
    /**
     * Initital form input values.
     *
     * @var {object}
     */
    protected initialValues: LoginValues = {
        uid: '',
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
            await signIn('credentials', {
                uid: values.uid,
                password: values.password,
                redirect: true,
                callbackUrl: '/'
            });

            helpers.setSubmitting(false);
        };
    }
}
