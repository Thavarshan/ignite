import Team from '@/teams/models/team.model';
import { Document } from 'mongoose';

export default interface User extends Document {
    name: string;
    username: string;
    email: string;
    password: string;
    phone?: string;
    roles?: string[];
    verification: string;
    verified: boolean;
    verificationExpires: Date;
    loginAttempts?: number;
    blockedAt?: Date;
    teamId: Partial<Team>;
}
