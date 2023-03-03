import Base from '@/database/models/base.model';
import ReplyContract from '../contracts/reply.contract';

export default class Reply extends Base<ReplyContract> {
    /**
     * The table associated with the model.
     *
     * @var {string}
     */
    protected readonly table: string = 'replies';
}
