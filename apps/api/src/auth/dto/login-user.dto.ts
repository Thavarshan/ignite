import { IsNotEmpty, MinLength, MaxLength, IsEmail, IsString } from 'class-validator';
import Credentials from '@/auth/contracts/credentials.contract';

export default class LoginUserDto implements Credentials {
    @IsNotEmpty()
    @IsString()
    @MinLength(5)
    @MaxLength(255)
    @IsEmail()
    public readonly email: string;

    @IsNotEmpty()
    @IsString()
    @MinLength(5)
    @MaxLength(1024)
    public readonly password: string;
}
