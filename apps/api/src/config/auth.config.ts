import { registerAs } from '@nestjs/config';

export default registerAs('auth', () => ({
    secretKey: process.env.JWT_SECRET,
    expiresIn: process.env.JWT_EXPIRATION || '3600s',
}));
