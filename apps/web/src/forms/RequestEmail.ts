import RequestEmailValues from '@/contracts/forms/RequestEmail';
import Form from './Form';
import { FormikHelpers } from 'formik';
import OnSubmitOptions from '@/contracts/forms/OnSubmitOptions';
// import { apiUrl, redirect } from '@/utils/helpers';

export default class RequestEmail extends Form {
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
    protected initialValues: RequestEmailValues = {
        email: ''
    };

    /**
     * Get the initial values of the form inputs,.
     *
     * @return  {RequestEmailValues}
     */
    public getInitialValues (): RequestEmailValues {
        return this.initialValues;
    }

    /**
     * The action to perform on form submit event.
     *
     * @param {RequestEmailValues}            values
     * @param {FormikHelpers}             helpers
     * @param {OnSubmitOptions|undefined} options
     *
     * @return  {Promise}
     */
    public async onSubmit (
        values: RequestEmailValues,
        helpers: FormikHelpers<RequestEmailValues>,
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
