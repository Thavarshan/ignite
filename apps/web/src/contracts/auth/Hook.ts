import Middleware from './Middleware';

export default interface Hook {
    middleware?: Middleware;
    redirectIfAuthenticated?: URL;
}
