import { Schema } from 'mongoose';
import validator from 'validator';
import PasswordResetRequestContract from '@/auth/contracts/password-reset-request.contract';

const PasswordResetRequestSchema = new Schema<PasswordResetRequestContract>({
    token: {
        type: String,
        required: true,
    },

    email: {
        type: String,
        lowercase: true,
        validate: validator.isEmail,
        maxlength: 255,
        minlength: 6,
        required: [true, 'EMAIL_IS_BLANK'],
    }
}, {
    versionKey: false,
    timestamps: true
});

export default PasswordResetRequestSchema;
