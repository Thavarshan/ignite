import { Document } from 'mongoose';

export default interface User extends Document {
    name: string;
    email: string;
    password: string;
    phone?: string;
    verification: string;
    verified: boolean;
    verificationExpires: Date;
    loginAttempts?: number;
    blockExpires?: Date;
}
