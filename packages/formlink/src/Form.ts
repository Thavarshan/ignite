import { AxiosInstance, AxiosStatic } from 'axios';
import _ from 'lodash';
import { serialize as objectToFormData } from 'object-to-formdata';
import HttpHandler from '@/HttpHandler';
import ErrorHandler from '@/ErrorHandler';
import { guardAgainstReservedFieldName } from '@/fieldNameValidation';

export default class Form {
    /**
     * All initial values after original data has beeen changed.
     *
     * @var {object}
     */
    protected initial: { [key: string]: any; } = {};

    /**
     * All form data.
     *
     * @var {object}
     */
    public data: { [key: string]: any; } = {};

    /**
     * List of options to apply to frontend throughout the form submission process.
     *
     * @var {object}
     */
    public options: { [key: string]: any; } = {
        resetOnSuccess: true,
        setInitialOnSuccess: false
    };

    /**
     * The form error handler instance.
     *
     * @var {ErrorHandler}
     */
    public errors?: ErrorHandler;

    /**
     * Indicates if the form has been submitted and is currently being processed.
     *
     * @var {boolean}
     */
    public processing = false;

    /**
     * Indicate if the form submittion is completed and was successful.
     *
     * @var {boolean}
     */
    public successful = false;

    /**
     * Indicate if the form was recently submitted, completed and was successful.
     *
     * @var {boolean}
     */
    public recentlySuccessful = false;

    /**
     * The default HTTP handler instance to use for form submission.
     *
     * @var {AxiosInstance|null}
     */
    protected http?: AxiosInstance;

    /**
     * Create a new Form instance.
     *
     * @param {object} data
     * @param {object} options
     *
     * @return  {void}
     */
    constructor (
        data: { [key: string]: any; },
        options?: { [key: string]: any; }
    ) {
        this.withData(data).withOptions(options);

        this.setHttpHandlerInstance(options?.axios);

        this.errors = new ErrorHandler();
    }

    /**
     * Create static instance of form object.
     *
     * @param   {object}  data
     * @param   {object}  options
     *
     * @return  {Form}
     */
    public static create (
        data: { [key: string]: any; },
        options?: { [key: string]: any; }
    ): Form {
        return new Form(data, options);
    }

    /**
     * Make GET request with currently attached data object to given endpoint.
     *
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    public async get (url: string, config: { [key: string]: any; } = {}): Promise<any> {
        return await this.submit('get', url, config);
    }

    /**
     * Make POST request with currently attached data object to given endpoint.
     *
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    public async post (url: string, config: { [key: string]: any; } = {}): Promise<any> {
        return await this.submit('post', url, config);
    }

    /**
     * Make PUT request with currently attached data object to given endpoint.
     *
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    public async put (url: string, config: { [key: string]: any; } = {}): Promise<any> {
        return await this.submit('put', url, config);
    }

    /**
     * Make PATCH request with currently attached data object to given endpoint.
     *
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    public async patch (url: string, config: { [key: string]: any; } = {}): Promise<any> {
        return await this.submit('patch', url, config);
    }

    /**
     * Make DELETE request with currently attached data object to given endpoint.
     *
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    public async delete (url: string, config: { [key: string]: any; } = {}): Promise<any> {
        return await this.submit('delete', url, config);
    }

    /**
     * Make given request type with currently attached data object to given endpoint.
     *
     * @param   {string}  method
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    protected async submit (
        method: string,
        url: string,
        config: { [key: string]: any; } = {}
    ): Promise<any> {
        this.startProcessing();

        this.validateRequestType(method);

        return await this.makeRequest(method, url, config)
            .then((response) => {
                this.errors?.clear();

                this.onSuccess(response);

                if (config.onSuccess) {
                    return config.onSuccess(response);
                }
            })
            .catch((errors) => {
                this.onFail(errors);

                if (config.onFail) {
                    return config.onFail(errors);
                }
            })
            .finally(() => {
                if (config.onFinish) {
                    return config.onFinish();
                }
            });
    }

    /**
     * Make a request to the given endpoint with the provided payload.
     *
     * @param  {string}  method
     * @param  {string}  url
     * @param  {object}  config
     *
     * @return  {Promise}
     */
    protected makeRequest (
        method: string,
        url: string,
        config: { [key: string]: any; }
    ): Promise<any> {
        method = method.toLowerCase();

        return new Promise((resolve, reject): void => {
            this.getHttpInstance()
                .request(_.merge({
                    url,
                    method,
                    ...config
                }, this.prepareDataForMethod(method)))
                .then((response: any) => resolve(response))
                .catch((error: any) => reject(error));
        });
    }

    /**
     * Prepare the data object for the given request type.
     *
     * @param   {string}  method
     *
     * @return  {object}
     */
    protected prepareDataForMethod (method: string): { [key: string]: any; } {
        if (method === 'get') {
            return { params: this.data };
        }

        return {
            data: this.hasFiles()
                ? objectToFormData(this.getData())
                : this.getData()
        };
    }

    /**
     * Actions to be performed on successful request response.
     *
     * @param   {object}  response
     *
     * @return  {object}
     */
    protected onSuccess (response: object): object {
        this.processing = false;

        if (!this.hasErrors()) {
            this.successful = true;
            this.recentlySuccessful = true;

            setTimeout(() => (this.recentlySuccessful = false), 2000);
        }

        if (_.get(this.options, 'resetOnSuccess')) {
            this.reset();
        } else if (_.get(this.options, 'setInitialOnSuccess')) {
            this.setInitialValues(this.getData());
        }

        return response;
    }

    /**
     * Actions to be performed on failed request attempt.
     *
     * @param   {any}  error
     *
     * @return  {void}
     */
    protected onFail (error: any): void {
        if (error !== null && !_.has(error, 'response')) {
            this.resetStatus();

            throw error;
        }

        this.errors?.record(
            this.extractErrors(_.get(error, 'response'))
        );

        this.resetStatus();
    }

    /**
     * Extract the errors from the response object.
     *
     * @param  {object} response
     *
     * @return {object}
     */
    protected extractErrors (response: object): object {
        const data: any = _.get(response, 'data');

        if (!data || typeof data !== 'object') {
            return { error: 'Something went wrong. Please try again.' };
        }

        if (data.errors) {
            return { ...data.errors };
        }

        if (data.message) {
            return { error: data.message };
        }

        return { ...data };
    }

    /**
     * Determine if the data attached contains file data.
     *
     * @return  {boolean}
     */
    protected hasFiles (): boolean {
        for (const property in this.initial) {
            if (this.hasFilesDeep(this.data[property])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if file data are available embedded into the data object.
     *
     * @param   {object}  object
     *
     * @return  {boolean}
     */
    protected hasFilesDeep (object: { [key: string]: any; }): boolean {
        if (!object) {
            return false;
        }

        if (typeof object === 'object') {
            for (const key in object) {
                if (_.has(object, key)) {
                    if (this.hasFilesDeep(object[key])) {
                        return true;
                    }
                }
            }
        }

        if (_.isArray(object)) {
            for (const key in object) {
                if (_.has(object, key)) {
                    return this.hasFilesDeep(object[key]);
                }
            }
        }

        return this.isFile(object);
    }

    /**
     * Determine if the given object is a file.
     *
     * @param   {any}      object
     *
     * @return  {boolean}
     */
    protected isFile (object: any): boolean {
        return object instanceof File || object instanceof FileList;
    }

    /**
     * Assign data to current instance of form object.
     *
     * @param   {object}  data
     *
     * @return  {Form}
     */
    public withData (data: { [key: string]: any; }): Form {
        this.setInitialValues(data);

        this.processing = false;
        this.successful = false;

        _.forEach(data, (value, key) => {
            guardAgainstReservedFieldName(key);

            this.data[key] = value;
        });

        return this;
    }

    /**
     * Set initial/original values of form data.
     *
     * @param   {object}  values
     *
     * @return  {void}
     */
    protected setInitialValues (values: { [key: string]: any; }): void {
        this.initial = {};

        _.merge(this.initial, values);
    }

    /**
     * Assign options to be used by current instance of form object.
     *
     * @param   {object}  options
     *
     * @return  {Form}
     */
    public withOptions (options?: { [key: string]: any; }): Form {
        this.options = _.merge(this.options, options || {});

        return this;
    }

    /**
     * Get all data as object assgined to form object.
     *
     * @return  {object}
     */
    public getData (): { [key: string]: any; } {
        const data: { [key: string]: any; } = {};

        _.forEach(this.initial, (value: any, key: string) => {
            data[key] = this.data[key];
        });

        return data;
    }

    /**
     * Save current data to initials object and empty current data registry.
     *
     * @return  {void}
     */
    public reset (): void {
        this.initial = _.merge(this.initial, this.data);

        _.forEach(this.initial, (key: string) => this.setField(key, null));
    }

    /**
     * Reset status properties.
     *
     * @return  {void}
     */
    protected resetStatus (): void {
        this.processing = false;
        this.successful = false;
        this.recentlySuccessful = false;
    }

    /**
     * Get the value of the specified field.
     *
     * @param   {string}  field
     *
     * @return  {any}
     */
    public getField (field: string): any {
        return this.data[field];
    }

    /**
     * Set the value of the specified field.
     *
     * @param   {string}  field
     * @param   {any}  value
     *
     * @return  {void}
     */
    public setField (field: string, value: any): void {
        _.set(this.data, field, value);
    }

    /**
     * Get all form options.
     *
     * @return  {object}
     */
    public getOptions (): { [key: string]: string; } {
        return this.options;
    }

    /**
     * Set custom options.
     *
     * @param   {string}  options
     *
     * @return  {void}
     */
    public setOptions (options: { [key: string]: any; }): void {
        this.options = _.merge(this.options, options);
    }

    /**
     * Set the default HttpHandler instance to use for form submission.
     *
     * @param   {AxiosStatic}  http
     *
     * @return  {Form}
     */
    public setHttpHandlerInstance (http?: AxiosStatic): Form {
        this.http = this.createHttpHandler(http);

        return this;
    }

    /**
     * Get the default HttpHandler instance.
     *
     * @return  {AxiosInstance}
     */
    public getHttpInstance (): AxiosInstance {
        return this.http || this.createHttpHandler();
    }

    /**
     * Create a new http handler instance.
     *
     * @param  {AxiosStatic}  client
     *
     * @return  {AxiosInstance}
     */
    protected createHttpHandler (client?: AxiosStatic): AxiosInstance {
        return (new HttpHandler(client))
            .acceptJson()
            .contentType('application/json')
            .setToken(this.options.token)
            .baseUrl(this.options.baseUrl)
            .createInstance();
    }

    /**
     * Set status properties to indicate form submission in progress.
     *
     * @return  {void}
     */
    protected startProcessing (): void {
        if (this.hasErrors()) {
            this.errors?.clear();
        }

        this.processing = true;
        this.successful = false;
    }

    /**
     * Determine if the inputs bound to form have any related error messages.
     *
     * @return  {boolean}
     */
    public hasErrors (): boolean {
        return this.errors?.any() || false;
    }

    /**
     * Determine f the given form filed bound to the form object has an error message.
     *
     * @param   {string}  field
     *
     * @return  {boolean}
     */
    public hasError (field: string): boolean {
        return this.hasErrors() && (this.errors?.has(field) || false);
    }

    /**
     * Get error message associated with the given form input.
     *
     * @param   {string}  field
     *
     * @return  {string|undefined}
     */
    public error (field: string): string | undefined {
        return this.errors?.get(field);
    }

    /**
     * Get all the errors associated with the form in a flat array.
     *
     * @return  {object[]}
     */
    public getAllErrors (): object[] {
        return this.errors?.flatten() || [];
    }

    /**
     * Determine if the given request type is supported.
     *
     * @param   {string}  method
     *
     * @return  {void}
     */
    protected validateRequestType (method: string): void {
        const methods = [
            'get',
            'post',
            'put',
            'patch',
            'delete',
            'head',
            'options'
        ];

        if (!methods.includes(method)) {
            throw new Error(
                `\`${method}\` is not a valid request type, ` +
                `must be one of: \`${methods.join('`, `')}\`.`
            );
        }
    }
}
