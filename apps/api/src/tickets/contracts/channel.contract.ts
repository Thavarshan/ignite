import { Document } from 'mongoose';

export default interface Channel extends Document {
    name: string;
    description?: string;
    slug: string;
}
