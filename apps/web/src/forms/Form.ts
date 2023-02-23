import { FormikHelpers } from 'formik';
import InputError from '@/contratcs/forms/InputError';
import _ from 'lodash';
import Config from '@/contratcs/forms/config';
import { apiUrl } from '@/utils/helpers';

export default abstract class Form {
    /**
     * Initital form input values.
     *
     * @var {object}
     */
    protected abstract initialValues: any;

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
     * @return  {Promise}
     */
    public abstract onSubmit (): (
        _values: { [key: string]: any; },
        _helpers: FormikHelpers<any>
    ) => Promise<void>;

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
            .catch(error => console.log(error));
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
