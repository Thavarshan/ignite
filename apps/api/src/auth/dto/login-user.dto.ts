import {
    IsNotEmpty,
    MinLength,
    MaxLength,
    IsEmail,
    IsString,
    Matches
} from 'class-validator';
import Credentials from '@/auth/contracts/credentials.contract';

export default class LoginUserDto implements Credentials {
    @IsNotEmpty()
    @IsEmail()
    @Matches(/^[a-z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)*$/i, { message: 'Email format is invalid' })
    public readonly email: string;

    @IsNotEmpty()
    @IsString()
    @MinLength(5)
    @MaxLength(1024)
    public readonly password: string;
}
