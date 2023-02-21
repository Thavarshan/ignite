import { Schema } from 'mongoose';
import validator from 'validator';
import { hash } from 'bcrypt';
import HookNextFunction from '@/users/contracts/hook.contract';
import Role from '@/users/enums/roles.enum';
import UserContract from '@/users/contracts/user.contract';

const UserSchema = new Schema<UserContract>({
    name: {
        type: String,
        minlength: 6,
        maxlength: 255,
        required: [true, 'NAME_IS_BLANK'],
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

    blockExpires: {
        type: Date,
        default: Date.now,
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
        if (!this.isModified('password')) {
            return next();
        }

        this.set('password', await hash(this.password, 12));

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
