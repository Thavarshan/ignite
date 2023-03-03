import InputValues from './InputValues';

export default interface ResetPassword extends InputValues {
    email: string;
    password: string;
    passwordConfirmation: string;
    token: string;
}
