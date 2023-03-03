import { Schema, SchemaTypes } from 'mongoose';
import ReplyContract from '@/tickets/contracts/reply.contract';

const ReplySchema = new Schema<ReplyContract>({
    body: {
        type: String,
        minlength: 3,
        maxlength: 255,
        required: [true, 'BODY_IS_BLANK'],
    },

    flagged: {
        type: Boolean,
        required: false,
        default: false
    },

    user: {
        type: SchemaTypes.ObjectId,
        ref: 'User',
        required: [true, 'USER_IS_BLANK']
    },

    ticket: {
        type: SchemaTypes.ObjectId,
        ref: 'Ticket',
        required: [true, 'TICKET_IS_BLANK']
    },
}, {
    versionKey: false,
    timestamps: true
});

export default ReplySchema;
