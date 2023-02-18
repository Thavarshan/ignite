import { Module } from '@nestjs/common';
import AuthService from './services/auth.service';
import UsersModule from '@/users/users.module';
import AuthController from './controllers/auth.controller';
import { MongooseModule } from '@nestjs/mongoose';
import UserSchema from '@/users/schemas/user.schema';
import UserRepository from '@/users/repositories/user.repository';

@Module({
    imports: [
        UsersModule,
        MongooseModule.forFeature([
            {
                name: 'User',
                schema: UserSchema
            }
        ])
    ],
    controllers: [AuthController],
    providers: [AuthService, UserRepository]
})
export class AuthModule { }
