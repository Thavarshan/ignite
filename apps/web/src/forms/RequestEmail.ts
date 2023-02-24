import RequestEmailValues from '@/contracts/forms/RequestEmail';
import Form from './Form';
import { FormikHelpers } from 'formik';
import { apiUrl, redirect } from '@/utils/helpers';
// import { apiUrl, redirect } from '@/utils/helpers';

export default class RequestEmail extends Form {
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
     * @return  {Promise}
     */
    public onSubmit (): (
        _values: { [key: string]: any; },
        _helpers: FormikHelpers<RequestEmailValues>
    ) => Promise<void> {
        return async (
            values: { [key: string]: any; },
            helpers: FormikHelpers<RequestEmailValues>
        ) => {
            this.post(apiUrl('request-email'), values, (response) => {
                if (response.status === 200) {
                    helpers.setSubmitting(false);

                    redirect('/');
                }
            });
        };
    }
}
