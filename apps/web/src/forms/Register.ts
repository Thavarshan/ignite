import RegisterValues from '@/contratcs/forms/Register';
import Form from './Form';
import { FormikHelpers } from 'formik';
import { apiUrl, redirect } from '@/utils/helpers';

export default class Register extends Form {
    /**
     * Initital form input values.
     *
     * @var {object}
     */
    protected initialValues: RegisterValues = {
        name: '',
        email: '',
        phone: '',
        password: '',
        passwordConfirmation: '',
        line1: '',
        line2: '',
        city: '',
        state: '',
        country: '',
        postalCode: ''
    };

    /**
     * The action to perform on form submit event.
     *
     * @return  {Promise}
     */
    public onSubmit (): (
        _values: { [key: string]: any; },
        _helpers: FormikHelpers<RegisterValues>
    ) => Promise<void> {
        return async (
            values: { [key: string]: any; },
            helpers: FormikHelpers<RegisterValues>
        ) => {
            this.post(
                apiUrl('register', this.config?.queries),
                values,
                (response) => {
                    if (response.status === 200) {
                        helpers.setSubmitting(false);

                        redirect('/');
                    }
                }
            );
        };
    }
}
