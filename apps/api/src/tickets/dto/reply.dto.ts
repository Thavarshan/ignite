import { IsNotEmpty, IsString } from 'class-validator';

export default class ReplyDto {
    @IsNotEmpty()
    @IsString()
    public body: string;

    @IsNotEmpty()
    @IsString()
    public user: string;

    @IsNotEmpty()
    @IsString()
    public ticket: string;
}
