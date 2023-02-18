import { IsNotEmpty, IsUUID } from 'class-validator';

export default class VerifyUuidDto {
    @IsNotEmpty()
    @IsUUID()
    public readonly verification: string;
}
