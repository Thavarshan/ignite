import { IsNotEmpty, MinLength, MaxLength, IsEmail, IsString, IsOptional } from 'class-validator';

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

    @IsOptional()
    @IsString()
    @MinLength(11)
    @MaxLength(1024)
    public readonly phone?: string;

    @IsOptional()
    @IsString()
    @MinLength(3)
    @MaxLength(1024)
    public readonly role?: string;
}
