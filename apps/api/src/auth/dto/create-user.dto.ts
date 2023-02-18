import { IsNotEmpty, MinLength, MaxLength, IsEmail, IsString } from 'class-validator';

export default class CreateUserDto {
    @IsNotEmpty()
    @IsString()
    @MinLength(5)
    @MaxLength(255)
    public readonly name: string;

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
