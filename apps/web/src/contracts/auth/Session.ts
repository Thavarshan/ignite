import { Session as NextAuthSession } from 'next-auth';

export default interface Session extends NextAuthSession {
    accessToken?: string;
}
