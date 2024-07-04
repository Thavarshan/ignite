import type { User } from '@/types/user';
import type { Team } from '@/types/team';
import type { Organisation } from '@/types/organisation';
import type { Report } from '@/types/report';
import type { Instruction } from '@/types/instruction';
import type { Payment } from '@/types/payment';
import type { Note } from '@/types/note';
import type { Police } from '@/types/police';

export interface GlobalSearchResults {
    instructions: Instruction[];
    reports: Report[];
    users: User[];
    police: Police[];
    organisations: Organisation[];
    teams: Team[];
    payments: Payment[];
    notes: Note[];
}
