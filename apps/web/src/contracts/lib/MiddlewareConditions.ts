import Guard from '../auth/Guard';

type MiddlewareConditions = {
    path: string,
    guard: Guard,
    redirectTo?: string | URL;
};

export default MiddlewareConditions;
