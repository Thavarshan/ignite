import FormValues from './FormValues';

export default interface LoginFormValues extends FormValues {
    email: string;
    password: string;
}
