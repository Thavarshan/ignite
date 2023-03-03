import {
    IsNotEmpty,
    IsEmail,
    Matches,
    MinLength,
    MaxLength,
    IsString
} from 'class-validator';
import Match from '@/auth/decorators/match.decorator';

export default class PasswordResetDto {
    @IsNotEmpty()
    @IsEmail()
    @Matches(/^[a-z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)*$/i, { message: 'Email format is invalid' })
    public readonly email: string;

    @IsString()
    @IsNotEmpty()
    @MinLength(8, { message: 'The min length of password is 8' })
    @MaxLength(20, { message: 'The password can\'t accept more than 20 characters' })
    public readonly password: string;

    @IsString()
    @IsNotEmpty()
    @MinLength(8)
    @MaxLength(20)
    @Match('password')
    public readonly passwordConfirmation: string;
}
