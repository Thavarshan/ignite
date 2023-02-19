export default interface AuthUser {
    _id: string;
    name: string;
    email: string;
    phone?: string;
    roles?: string[];
    verification: string;
    verified: boolean;
    verificationExpires: Date;
    loginAttempts?: number;
    blockExpires?: Date;
    accessToken?: string;
    refreshToken?: string;
}
