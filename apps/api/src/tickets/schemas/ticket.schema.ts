import { Schema, SchemaTypes } from 'mongoose';
import HookNextFunction from '@/contracts/hook.contract';
import TicketContract from '@/tickets/contracts/ticket.contract';
import TicketEnum from '@/tickets/enums/ticket.enum';
import slug from 'slug';

const TicketSchema = new Schema<TicketContract>({
    title: {
        type: String,
        minlength: 6,
        maxlength: 255,
        required: [true, 'TITLE_IS_BLANK'],
    },

    body: {
        type: String,
        minlength: 3,
        maxlength: 1024,
        required: [true, 'BODY_IS_BLANK'],
    },

    slug: {
        type: String,
        lowercase: true,
        slug: 'title',
        required: false,
    },

    priority: {
        type: String,
        required: [true, 'PRIORITY_IS_BLANK'],
        enum: TicketEnum,
        default: TicketEnum.LOW_PRIORITY
    },

    status: {
        type: String,
        required: [true, 'STATUS_IS_BLANK'],
        enum: TicketEnum,
        default: TicketEnum.OPEN
    },

    locked: {
        type: Boolean,
        default: false,
    },

    user: {
        type: SchemaTypes.ObjectId,
        ref: 'User',
        required: [true, 'USER_IS_BLANK']
    },

    agent: {
        type: SchemaTypes.ObjectId,
        ref: 'User',
        required: false
    },

    channel: {
        type: SchemaTypes.ObjectId,
        ref: 'Channel',
        required: [true, 'CHANNEL_IS_BLANK']
    },

    team: {
        type: SchemaTypes.ObjectId,
        ref: 'Team',
        required: [true, 'TEAM_IS_BLANK']
    },
}, {
    versionKey: false,
    timestamps: true,
    toJSON: {
        virtuals: true,
    },
});

export const preSaveHook = async function (next: HookNextFunction) {
    try {
        if (this.isModified('title')) {
            this.set('slug', slug(this.title));
        }

        return next();
    } catch (error) {
        return next(error);
    }
};

TicketSchema.pre<TicketContract>('save', preSaveHook);

TicketSchema.virtual('replies', {
    ref: 'Reply',
    localField: '_id',
    foreignField: 'ticket',
    justOne: false
});

export default TicketSchema;
