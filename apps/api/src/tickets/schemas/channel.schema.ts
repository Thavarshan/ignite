import { Schema } from 'mongoose';
import HookNextFunction from '@/contracts/hook.contract';
import ChannelContract from '@/tickets/contracts/channel.contract';
import slug from 'slug';

const ChannelSchema = new Schema<ChannelContract>({
    name: {
        type: String,
        minlength: 3,
        maxlength: 255,
        required: [true, 'NAME_IS_BLANK'],
    },

    description: {
        type: String,
        required: false,
    },

    slug: {
        type: String,
        lowercase: true,
        slug: 'title',
        required: false,
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
        if (this.isModified('name')) {
            this.set('slug', slug(this.name));
        }

        return next();
    } catch (error) {
        return next(error);
    }
};

ChannelSchema.pre<ChannelContract>('save', preSaveHook);

ChannelSchema.virtual('tickets', {
    ref: 'Ticket',
    localField: '_id',
    foreignField: 'channel',
    justOne: false
});

export default ChannelSchema;
