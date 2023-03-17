import * as jose from 'jose';
import JwtPayload from '../Contracts/Auth/JwtPayload';

const verify = async (token: string, secretKey: string): Promise<JwtPayload> => {
    const { payload } = await jose.jwtVerify(
        token, new TextEncoder().encode(secretKey)
    );

    return payload;
};

export { verify };
