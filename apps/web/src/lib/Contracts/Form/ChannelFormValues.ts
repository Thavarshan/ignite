import FormValues from './FormValues';

export default interface ChannelFormValues extends FormValues {
    id?: string,
    name: string;
    description?: string;
}
