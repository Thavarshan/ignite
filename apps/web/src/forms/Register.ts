import RegisterValues from '@/contracts/forms/Register';
import Form from './Form';
import { FormikHelpers } from 'formik';
import OnSubmitOptions from '@/contracts/forms/OnSubmitOptions';
import Router from 'next/router';

export default class Register extends Form {
    /**
     * The URL the form is to submit data to.
     *
     * @var {string}
     */
    protected url = '/register';

    /**
     * Initital form input values.
     *
     * @var {object}
     */
    protected initialValues: RegisterValues = {
        name: '',
        email: '',
        phone: undefined,
        password: '',
        role: undefined
    };

    /**
     * The action to perform on form submit event.
     *
     * @param {RegisterValues}            values
     * @param {FormikHelpers}             helpers
     * @param {OnSubmitOptions|undefined} options
     *
     * @return  {Promise}
     */
    public async onSubmit (
        values: RegisterValues,
        helpers: FormikHelpers<RegisterValues>,
        options?: OnSubmitOptions
    ): Promise<any> {
        return await this.submit(
            () => {
                helpers.resetForm();

                Router.push('/auth/login');
            },
            values,
            helpers,
            options
        );
    }
}
