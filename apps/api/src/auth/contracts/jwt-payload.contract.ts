export default interface JwtPayload {
    username: string;
    sub: string;
    iat: string;
    exp: string;
}
