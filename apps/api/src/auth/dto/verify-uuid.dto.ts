import { IsNotEmpty, IsString, IsUUID } from 'class-validator';

export default class VerifyUuidDto {
    @IsNotEmpty()
    @IsUUID()
    @IsString()
    public readonly verification: string;
}
