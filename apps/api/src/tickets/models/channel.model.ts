import Base from '@/database/models/base.model';
import ChannelContract from '../contracts/channel.contract';

export default class Channel extends Base<ChannelContract> {
    /**
     * The table associated with the model.
     *
     * @var {string}
     */
    protected readonly table: string = 'channels';
}
