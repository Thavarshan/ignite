import { Schema, SchemaTypes } from 'mongoose';
import validator from 'validator';
import { hash } from 'bcrypt';
import HookNextFunction from '@/contracts/hook.contract';
import Role from '@/users/enums/roles.enum';
import UserContract from '@/users/contracts/user.contract';
import { uniqueNamesGenerator, Config, starWars } from 'unique-names-generator';
import slug from 'slug';

const UserSchema = new Schema<UserContract>({
    name: {
        type: String,
        minlength: 6,
        maxlength: 255,
        required: [true, 'NAME_IS_BLANK'],
    },

    username: {
        type: String,
        minlength: 6,
        maxlength: 255,
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

    password: {
        type: String,
        minlength: 5,
        maxlength: 1024,
        required: [true, 'PASSWORD_IS_BLANK'],
    },

    phone: {
        type: String,
        minlength: 11,
        maxlength: 11,
        required: false
    },

    roles: {
        type: [String],
        default: [Role.USER],
    },

    verification: {
        type: String,
        validate: validator.isUUID,
    },

    verified: {
        type: Boolean,
        default: false,
    },

    verificationExpires: {
        type: Date,
        default: Date.now,
    },

    loginAttempts: {
        type: Number,
        default: 0,
    },

    blockedAt: {
        required: false,
        type: Date,
        default: undefined,
    },

    teamId: {
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
            this.set('username', slug(uniqueNamesGenerator({
                dictionaries: [starWars]
            } as Config)));
        }

        if (this.isModified('password')) {
            this.set('password', await hash(this.password, 12));
        }

        return next();
    } catch (error) {
        return next(error);
    }
};

UserSchema.pre('save', preSaveHook);

UserSchema.virtual('posts', {
    ref: 'Post', // must be changed
    localField: '_id',
    foreignField: 'userId',
});

export default UserSchema;
