import { FormikHelpers } from 'formik';
import RegisterFormValues from '../Contracts/Form/RegisterFormValues';
import Http from '../Http/Client';
import { AxiosStatic } from 'axios';
import Form from './Form';

export default class Register extends Form {
    protected values: RegisterFormValues = {
        name: '',
        email: '',
        password: '',
        passwordConfirmation: '',
        phone: ''
    };

    public async handleSubmit (
        values: RegisterFormValues,
        _helpers: FormikHelpers<RegisterFormValues>
    ): Promise<any> {
        const http = Http.make();

        return http.post('/register', values)
            .then(response => response.data)
            .catch(error => {
                const e: any = error.response.data;

                throw new Error(JSON.stringify(e.message));
            });
    }
}
