import * as mongoose from 'mongoose';
import validator from 'validator';
import * as bcrypt from 'bcrypt';
import HookNextFunction from '@/users/contracts/hook.contract';

const { Schema } = mongoose;

const UserSchema = new Schema({
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
        default: ['user'],
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
});

UserSchema.pre('save', async function (next: HookNextFunction) {
    try {
        if (!this.isModified('password')) {
            return next();
        }

        this['password'] = await bcrypt.hash(this['password'], 10);

        return next();
    } catch (error) {
        return next(error);
    }
});

export default UserSchema;
