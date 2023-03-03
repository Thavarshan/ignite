import Base from '@/database/models/base.model';
import TicketContract from '../contracts/ticket.contract';

export default class Ticket extends Base<TicketContract> {
    /**
     * The table associated with the model.
     *
     * @var {string}
     */
    protected readonly table: string = 'tickets';
}
