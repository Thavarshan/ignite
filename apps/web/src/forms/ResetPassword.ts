import ResetPasswordValues from '@/contracts/forms/ResetPassword';
import Form from './Form';
import { FormikHelpers } from 'formik';
import OnSubmitOptions from '@/contracts/forms/OnSubmitOptions';
// import { apiUrl, redirect } from '@/utils/helpers';

export default class ResetPassword extends Form {
    /**
     * The URL the form is to submit data to.
     *
     * @var {string}
     */
    protected url = '/reset-password';

    /**
     * Initital form input values.
     *
     * @var {object}
     */
    protected initialValues: ResetPasswordValues = {
        email: '',
        password: '',
        passwordConfirmation: '',
        token: ''
    };

    /**
     * The action to perform on form submit event.
     *
     * @param {ResetPasswordValues}            values
     * @param {FormikHelpers}                  helpers
     * @param {OnSubmitOptions|undefined}      options
     *
     * @return  {Promise}
     */
    public async onSubmit (
        values: ResetPasswordValues,
        helpers: FormikHelpers<ResetPasswordValues>,
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
