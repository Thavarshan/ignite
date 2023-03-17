type JwtPayload = {
    username?: string,
    sub?: string,
    iat?: number,
    exp?: number;
};

export default JwtPayload;
