import FormValues from './FormValues';

export default interface RegisterFormValues extends FormValues {
    name: string;
    email: string;
    password: string;
    passwordConfirmation: string;
    phone?: string;
}
