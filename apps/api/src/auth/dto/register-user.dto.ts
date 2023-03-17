import { PartialType } from '@nestjs/mapped-types';
import UserDto from '@/users/dto/user.dto';

export default class RegisterUserDto extends PartialType(UserDto) { }
