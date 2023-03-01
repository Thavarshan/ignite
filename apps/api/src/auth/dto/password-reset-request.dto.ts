import { IsNotEmpty, IsEmail, Matches } from 'class-validator';

export default class PasswordResetRequestDto {
    @IsNotEmpty()
    @IsEmail()
    @Matches(/^[a-z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)*$/i)
    public readonly email: string;
}
