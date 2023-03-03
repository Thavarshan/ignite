import ForgotPasswordValues from '@/contracts/forms/ForgotPassword';
import { FormikHelpers } from 'formik';
import OnSubmitOptions from '../contracts/forms/OnSubmitOptions';
import Form from './Form';

export default class ForgotPassword extends Form {
    /**
     * The URL the form is to submit data to.
     *
     * @var {string}
     */
    protected url = '/password-reset-request';

    /**
     * Initital form input values.
     *
     * @var {object}
     */
    protected initialValues: ForgotPasswordValues = {
        email: ''
    };

    /**
     * The action to perform on form submit event.
     *
     * @param {ForgotPasswordValues}               values
     * @param {FormikHelpers}             helpers
     * @param {OnSubmitOptions|undefined} options
     *
     * @return  {Promise}
     */
    public async onSubmit (
        values: ForgotPasswordValues,
        helpers: FormikHelpers<ForgotPasswordValues>,
        options?: OnSubmitOptions
    ): Promise<any> {
        return await this.submit(
            () => helpers.resetForm(),
            values,
            helpers,
            options
        );
    }
}
