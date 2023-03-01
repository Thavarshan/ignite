import ForgotPasswordValues from '@/contracts/forms/ForgotPassword';
import { FormikHelpers } from 'formik';
import { setCookie } from 'cookies-next';
import OnSubmitOptions from '../contracts/forms/OnSubmitOptions';
import Form from './Form';

export default class ForgotPassword extends Form {
    /**
     * The URL the form is to submit data to.
     *
     * @var {string}
     */
    protected url = '/forgot-password';

    /**
     * Initital form input values.
     *
     * @var {object}
     */
    protected initialValues: ForgotPasswordValues = {
        email: ''
    };

    /**
     * Get the initial values of the form inputs,.
     *
     * @return  {ForgotPasswordValues}
     */
    public getInitialValues (): ForgotPasswordValues {
        return this.initialValues;
    }

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
            (response) => {
                const { accessToken: token } = response.data;

                setCookie('token', token, { sameSite: true });

                helpers.resetForm();
            },
            values,
            helpers,
            options
        );
    }
}
