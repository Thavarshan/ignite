import { IsNotEmpty, IsOptional, IsString } from 'class-validator';

export default class TicketDto {
    @IsNotEmpty()
    @IsString()
    public title: string;

    @IsNotEmpty()
    @IsString()
    public body: string;

    @IsNotEmpty()
    @IsString()
    public channel: string;

    @IsNotEmpty()
    @IsString()
    public user: string;

    @IsNotEmpty()
    @IsString()
    public agent?: string;

    @IsOptional()
    @IsString()
    public team?: string;
}
