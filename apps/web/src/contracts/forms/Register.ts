import InputValues from './InputValues';

export default interface Register extends InputValues {
    name: string;
    email: string;
    phone?: string;
    password: string;
    role?: string;
}
