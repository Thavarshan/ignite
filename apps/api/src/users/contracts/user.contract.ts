import Team from '@/teams/models/team.model';
import { Document } from 'mongoose';
import Roles from '../enums/roles.enum';

export default interface User extends Document {
    name: string;
    username: string;
    email: string;
    password: string;
    phone?: string;
    role?: Roles.USER | Roles.AGENT | Roles.ADMIN;
    verified: boolean;
    loginAttempts?: number;
    blockedAt?: Date;
    team: Partial<Team>;
}
