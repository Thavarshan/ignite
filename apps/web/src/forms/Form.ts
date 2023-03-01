import { FormikHelpers } from 'formik';
import InputError from '@/contracts/forms/InputError';
import _ from 'lodash';
import Config from '@/contracts/forms/config';
import OnSubmitOptions from '../contracts/forms/OnSubmitOptions';
import InputValues from '@/contracts/forms/InputValues';
import axios from '@/utils/axios';
import FormMethod from '@/contracts/forms/FormMethod';

export default abstract class Form {
    /**
     * The URL the form is to submit data to.
     *
     * @var {string}
     */
    protected url = '/';

    /**
     * The request method used when form is being submitted.
     *
     * @var {string}
     */
    protected method: FormMethod = 'post';

    /**
     * Initital form input values.
     *
     * @var {InputValues}
     */
    protected initialValues: InputValues;

    /**
     * Form input configs.
     *
     * @var {Config|undefined}
     */
    protected config?: Config = {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    };

    /**
     * Create new Form instance.
     *
     * @param   {InputValues|undefined}  values
     *
     * @return  {void}
     */
    constructor (values?: InputValues) {
        this.initialValues = _.merge(this.initialValues, values || {});
    }

    /**
     * Set form input configs.
     *
     * @param   {Config}  config
     *
     * @return  {void}
     */
    public setConfig (config: { [key: string]: any; }): void {
        this.config = _.merge(this.config, config);
    }

    /**
     * Get the initial values of the form inputs,.
     *
     * @return  {object}
     */
    public getInitialValues (): any {
        return this.initialValues;
    }

    /**
     * The action to perform on form submit event.
     *
     * @param {LoginValues}               _values
     * @param {FormikHelpers}             _helpers
     * @param {OnSubmitOptions|undefined} _options
     *
     * @return  {Promise}
     */
    public abstract onSubmit (
        _values: InputValues,
        _helpers: FormikHelpers<InputValues>,
        _options?: OnSubmitOptions
    ): Promise<any>;

    /**
     * The action to perform on form submit event.
     *
     * @param {function}                  action
     * @param {LoginValues}               values
     * @param {FormikHelpers}             helpers
     * @param {OnSubmitOptions|undefined} options
     *
     * @return  {Promise}
     */
    public async submit (
        action: (_response: any) => any,
        values: InputValues,
        helpers: FormikHelpers<InputValues>,
        options?: OnSubmitOptions
    ): Promise<any> {
        return axios[this.method](this.url, { ...values })
            .then((response): any => {
                const result = action(response);

                if (result) {
                    return result;
                }

                if (options?.onSuccess) {
                    return options.onSuccess(response);
                }
            })
            .catch(error => {
                if (options?.onFail) {
                    return options.onFail(error);
                }

                throw error;
            })
            .finally((): void => {
                if (options?.onFinish) {
                    options.onFinish();
                }

                helpers.setSubmitting(false);
            });
    }

    /**
     * Make post request to given URL with provided data.
     *
     * @param {string} url
     * @param {object} values
     * @param {Function} action
     *
     * @return  {void}
     */
    public post (
        url: string,
        values: { [key: string]: any; },
        action: (_response: any) => void
    ): void {
        fetch(url, _.merge(this.config, { body: JSON.stringify(values) }))
            .then(response => action(response))
            .catch(error => { throw error; });
    }

    /**
     * Set input errors.
     *
     * @param   {InputError[]}  errors
     *
     * @return  {void}
     */
    protected parseErrors (
        errors: { response: any; }
    ): { [key: string]: string; } {
        const errorsList: { [key: string]: string; } = {};
        const { response } = errors;

        if (response.status === 422) {
            _.forEach(response.data, (error: InputError) => {
                errorsList[error.field] = error.message;
            });
        } else {
            throw new Error(response.data.message);
        }

        return errorsList;
    }
}
