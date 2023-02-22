import { AxiosInstance, AxiosStatic } from 'axios';
import ErrorHandler from './ErrorHandler';
export default class Form {
    /**
     * All initial values after original data has beeen changed.
     *
     * @var {object}
     */
    protected initial: {
        [key: string]: any;
    };
    /**
     * All form data.
     *
     * @var {object}
     */
    data: {
        [key: string]: any;
    };
    /**
     * List of options to apply to frontend throughout the form submission process.
     *
     * @var {object}
     */
    options: {
        [key: string]: any;
    };
    /**
     * The form error handler instance.
     *
     * @var {ErrorHandler}
     */
    errors?: ErrorHandler;
    /**
     * Indicates if the form has been submitted and is currently being processed.
     *
     * @var {boolean}
     */
    processing: boolean;
    /**
     * Indicate if the form submittion is completed and was successful.
     *
     * @var {boolean}
     */
    successful: boolean;
    /**
     * Indicate if the form was recently submitted, completed and was successful.
     *
     * @var {boolean}
     */
    recentlySuccessful: boolean;
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
    constructor(data: {
        [key: string]: any;
    }, options?: {
        [key: string]: any;
    });
    /**
     * Create static instance of form object.
     *
     * @param   {object}  data
     * @param   {object}  options
     *
     * @return  {Form}
     */
    static create(data: {
        [key: string]: any;
    }, options?: {
        [key: string]: any;
    }): Form;
    /**
     * Make GET request with currently attached data object to given endpoint.
     *
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    get(url: string, config?: {
        [key: string]: any;
    }): Promise<any>;
    /**
     * Make POST request with currently attached data object to given endpoint.
     *
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    post(url: string, config?: {
        [key: string]: any;
    }): Promise<any>;
    /**
     * Make PUT request with currently attached data object to given endpoint.
     *
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    put(url: string, config?: {
        [key: string]: any;
    }): Promise<any>;
    /**
     * Make PATCH request with currently attached data object to given endpoint.
     *
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    patch(url: string, config?: {
        [key: string]: any;
    }): Promise<any>;
    /**
     * Make DELETE request with currently attached data object to given endpoint.
     *
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    delete(url: string, config?: {
        [key: string]: any;
    }): Promise<any>;
    /**
     * Make given request type with currently attached data object to given endpoint.
     *
     * @param   {string}  method
     * @param   {string}  url
     * @param   {object}  config
     *
     * @return  {Promise}
     */
    protected submit(method: string, url: string, config?: {
        [key: string]: any;
    }): Promise<any>;
    /**
     * Make a request to the given endpoint with the provided payload.
     *
     * @param  {string}  method
     * @param  {string}  url
     * @param  {object}  config
     *
     * @return  {Promise}
     */
    protected makeRequest(method: string, url: string, config: {
        [key: string]: any;
    }): Promise<any>;
    /**
     * Prepare the data object for the given request type.
     *
     * @param   {string}  method
     *
     * @return  {object}
     */
    protected prepareDataForMethod(method: string): {
        [key: string]: any;
    };
    /**
     * Actions to be performed on successful request response.
     *
     * @param   {object}  response
     *
     * @return  {object}
     */
    protected onSuccess(response: object): object;
    /**
     * Actions to be performed on failed request attempt.
     *
     * @param   {any}  error
     *
     * @return  {void}
     */
    protected onFail(error: any): void;
    /**
     * Extract the errors from the response object.
     *
     * @param  {object} response
     *
     * @return {object}
     */
    protected extractErrors(response: object): object;
    /**
     * Determine if the data attached contains file data.
     *
     * @return  {boolean}
     */
    protected hasFiles(): boolean;
    /**
     * Determine if file data are available embedded into the data object.
     *
     * @param   {object}  object
     *
     * @return  {boolean}
     */
    protected hasFilesDeep(object: {
        [key: string]: any;
    }): boolean;
    /**
     * Determine if the given object is a file.
     *
     * @param   {any}      object
     *
     * @return  {boolean}
     */
    protected isFile(object: any): boolean;
    /**
     * Assign data to current instance of form object.
     *
     * @param   {object}  data
     *
     * @return  {Form}
     */
    withData(data: {
        [key: string]: any;
    }): Form;
    /**
     * Set initial/original values of form data.
     *
     * @param   {object}  values
     *
     * @return  {void}
     */
    protected setInitialValues(values: {
        [key: string]: any;
    }): void;
    /**
     * Assign options to be used by current instance of form object.
     *
     * @param   {object}  options
     *
     * @return  {Form}
     */
    withOptions(options?: {
        [key: string]: any;
    }): Form;
    /**
     * Get all data as object assgined to form object.
     *
     * @return  {object}
     */
    getData(): {
        [key: string]: any;
    };
    /**
     * Save current data to initials object and empty current data registry.
     *
     * @return  {void}
     */
    reset(): void;
    /**
     * Reset status properties.
     *
     * @return  {void}
     */
    protected resetStatus(): void;
    /**
     * Get the value of the specified field.
     *
     * @param   {string}  field
     *
     * @return  {any}
     */
    getField(field: string): any;
    /**
     * Set the value of the specified field.
     *
     * @param   {string}  field
     * @param   {any}  value
     *
     * @return  {void}
     */
    setField(field: string, value: any): void;
    /**
     * Get all form options.
     *
     * @return  {object}
     */
    getOptions(): {
        [key: string]: string;
    };
    /**
     * Set custom options.
     *
     * @param   {string}  options
     *
     * @return  {void}
     */
    setOptions(options: {
        [key: string]: any;
    }): void;
    /**
     * Set the default HttpHandler instance to use for form submission.
     *
     * @param   {AxiosStatic}  http
     *
     * @return  {Form}
     */
    setHttpHandlerInstance(http?: AxiosStatic): Form;
    /**
     * Get the default HttpHandler instance.
     *
     * @return  {AxiosInstance}
     */
    getHttpInstance(): AxiosInstance;
    /**
     * Create a new http handler instance.
     *
     * @param  {AxiosStatic}  client
     *
     * @return  {AxiosInstance}
     */
    protected createHttpHandler(client?: AxiosStatic): AxiosInstance;
    /**
     * Set status properties to indicate form submission in progress.
     *
     * @return  {void}
     */
    protected startProcessing(): void;
    /**
     * Determine if the inputs bound to form have any related error messages.
     *
     * @return  {boolean}
     */
    hasErrors(): boolean;
    /**
     * Determine f the given form filed bound to the form object has an error message.
     *
     * @param   {string}  field
     *
     * @return  {boolean}
     */
    hasError(field: string): boolean;
    /**
     * Get error message associated with the given form input.
     *
     * @param   {string}  field
     *
     * @return  {string|undefined}
     */
    error(field: string): string | undefined;
    /**
     * Get all the errors associated with the form in a flat array.
     *
     * @return  {object[]}
     */
    getAllErrors(): object[];
    /**
     * Determine if the given request type is supported.
     *
     * @param   {string}  method
     *
     * @return  {void}
     */
    protected validateRequestType(method: string): void;
}
//# sourceMappingURL=Form.d.ts.map