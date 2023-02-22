import _ from 'lodash';

export default class ErrorHandler {
    /**
     * All errors registered in all fields.
     *
     * @var {object}
     */
    protected errors: { [key: string]: any; };

    /**
     * Create new errors handler instance.
     *
     * @return {void}
     */
    constructor () {
        this.errors = {};
    }

    /**
     * Get first error message for given field.
     *
     * @param  {string} field
     *
     * @return {string}
     */
    get (field: string): string | undefined {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    /**
     * Get all the error messages for the given field.
     *
     * @param  {string} field
     *
     * @return {Array}
     */
    getAll (field: string): string[] {
        const result = this.errors[field] || [];

        return _.isArray(result) ? result : [result];
    }

    /**
     * Determine if the given field has an error.
     *
     * @param  {string}  field
     *
     * @return {boolean}
     */
    has (field: string): boolean {
        return _.has(this.errors, field);
    }

    /**
     * Get all the errors in a flat array.
     *
     * @return {any[]}
     */
    flatten (): any[] {
        return Object.values(this.errors)
            .reduce((carry, element) => {
                return carry.concat(element);
            }, []);
    }

    /**
     * Get all the errors.
     *
     * @return {object}
     */
    all (): object {
        return this.errors;
    }

    /**
     * Record error messages object.
     *
     * @param  {object} errors
     *
     * @return {void}
     */
    record (errors: object): void {
        this.errors = errors;
    }

    /**
     * Clear message of given error field.
     *
     * @param  {string} field
     *
     * @return {void}
     */
    clear (field?: string): void {
        if (field) {
            delete this.errors[field];

            return;
        }

        this.errors = {};
    }

    /**
     * Determine if any error messages are available in all registered fields.
     *
     * @return {boolean}
     */
    any (): boolean {
        if (!this.errors) {
            return true;
        }

        return Object.keys(this.errors).length > 0;
    }
}
