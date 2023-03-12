import { IsNotEmpty, IsOptional, IsString } from 'class-validator';

export default class ChannelDto {
    @IsNotEmpty()
    @IsString()
    public name: string;

    @IsOptional()
    @IsString()
    public description?: string;
}
