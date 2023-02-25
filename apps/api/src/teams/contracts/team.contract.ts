import User from '@/users/models/user.model';
import { Document } from 'mongoose';

export default interface Team extends Document {
    name: string;
    slug: string;
    email: string;
    phone?: string;
    description?: string;
    owner?: Partial<User>;
}
