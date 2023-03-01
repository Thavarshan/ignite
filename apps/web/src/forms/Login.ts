import LoginValues from '@/contracts/forms/Login';
import { FormikHelpers } from 'formik';
import { setCookie } from 'cookies-next';
import OnSubmitOptions from '../contracts/forms/OnSubmitOptions';
import Form from './Form';

export default class Login extends Form {
    /**
     * The URL the form is to submit data to.
     *
     * @var {string}
     */
    protected url = '/login';

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
     * @param {LoginValues}               values
     * @param {FormikHelpers}             helpers
     * @param {OnSubmitOptions|undefined} options
     *
     * @return  {Promise}
     */
    public async onSubmit (
        values: LoginValues,
        helpers: FormikHelpers<LoginValues>,
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
