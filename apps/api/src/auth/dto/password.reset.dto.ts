import {
    IsNotEmpty,
    IsEmail,
    Matches,
    MinLength,
    MaxLength,
    IsString
} from 'class-validator';

export default class PasswordResetDto {
    @IsNotEmpty()
    @IsEmail()
    @Matches(/^[a-z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)*$/i, { message: 'Email format is invalid' })
    public readonly email: string;

    @IsNotEmpty()
    @MinLength(8, { message: 'The min length of password is 8' })
    @MaxLength(20, { message: 'The password can\'t accept more than 20 characters' })
    public readonly password: string;

    @IsString()
    @MinLength(4)
    @MaxLength(20)
    @Matches('password')
    public readonly passwordConfirmation: string;
}
