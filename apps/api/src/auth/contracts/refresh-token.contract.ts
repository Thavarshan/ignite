import User from '@/users/contracts/user.contract';
import { Document } from 'mongoose';

export interface RefreshToken extends Document {
    userId: User;
    refreshToken: string;
    ip: string;
    browser: string;
    country: string;
}
