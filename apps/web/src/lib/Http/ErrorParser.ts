import ErrorResponse from '@/lib/Contracts/Http/Responses/ErrorResponse';
import _ from 'lodash';

export default class ErrorParser {
    public static parse (error: ErrorResponse): ErrorResponse | ErrorResponse[] {
        if (!_.isArray(error.message)) {
            return error;
        }

        const errors: ErrorResponse[] = [];

        _.forEach(error.message, (message) => {
            errors.push({
                error: error.error,
                message,
                statusCode: error.statusCode
            });
        });

        return errors;
    }
}
