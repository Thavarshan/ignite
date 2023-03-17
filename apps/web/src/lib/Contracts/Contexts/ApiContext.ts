import User from '../Auth/User';

export default interface ApiContext {
    users: User[] | null;
}
