import { PartialType } from '@nestjs/mapped-types';
import UserDto from '@/users/dto/user.dto';
import { IsOptional, IsString } from 'class-validator';

export default class RegisterUserDto extends PartialType(UserDto) {
    @IsOptional()
    @IsString()
    public readonly team?: string;
}
