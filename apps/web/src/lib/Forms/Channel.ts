import { FormikHelpers } from 'formik';
import ChannelFormValues from '../Contracts/Form/ChannelFormValues';
import Form from './Form';

export default class Channel extends Form {
    protected values: ChannelFormValues = {
        name: '',
        description: ''
    };

    public async handleSubmit (
        _values: ChannelFormValues,
        _helpers?: FormikHelpers<ChannelFormValues>
    ): Promise<any> {
        return await this.client().resources('channels');
    }

    public async handleCreateSubmit (
        values: ChannelFormValues,
        _helpers?: FormikHelpers<ChannelFormValues>
    ): Promise<any> {
        const handle = await this.handleSubmit(values);

        await handle.create(values);
    }

    public async handleEditSubmit (
        values: ChannelFormValues,
        _helpers?: FormikHelpers<ChannelFormValues>
    ): Promise<any> {
        const handle = await this.handleSubmit(values);

        await handle.update(values.id, values);
    }

    public async handleDelete (values: ChannelFormValues): Promise<void> {
        const handle = await this.handleSubmit(values);

        await handle.delete(values.id);
    }
}
