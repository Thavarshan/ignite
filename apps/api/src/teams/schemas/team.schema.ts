import { Schema, SchemaTypes } from 'mongoose';
import validator from 'validator';
import HookNextFunction from '@/contracts/hook.contract';
import TeamContract from '@/teams/contracts/team.contract';
import slug from 'slug';

const TeamSchema = new Schema<TeamContract>({
    name: {
        type: String,
        minlength: 6,
        maxlength: 255,
        required: [true, 'NAME_IS_BLANK'],
    },

    slug: {
        type: String,
        minlength: 6,
        maxlength: 255,
        slug: 'name',
        required: false,
    },

    email: {
        type: String,
        lowercase: true,
        validate: validator.isEmail,
        maxlength: 255,
        minlength: 6,
        required: [true, 'EMAIL_IS_BLANK'],
    },

    description: {
        type: String,
        minlength: 5,
        maxlength: 1024,
        required: false,
    },

    phone: {
        type: String,
        minlength: 11,
        maxlength: 11,
        required: false
    },

    owner: {
        type: SchemaTypes.ObjectId,
        ref: 'User',
        required: false
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

TeamSchema.pre<TeamContract>('save', preSaveHook);

TeamSchema.virtual('members', {
    ref: 'User',
    localField: '_id',
    foreignField: 'team',
});

export default TeamSchema;
