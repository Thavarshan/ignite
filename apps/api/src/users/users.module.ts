import { Module } from '@nestjs/common';
import UsersService from '@/users/services/user.service';
import UsersController from '@/users/controllers/users.controller';
import UserRepository from '@/users/repositories/user.repository';
import { MongooseModule } from '@nestjs/mongoose';
import UserSchema from './schemas/user.schema';

@Module({
    imports: [
        MongooseModule.forFeature([
            {
                name: 'User',
                schema: UserSchema
            }
        ])
    ],

    controllers: [UsersController],

    providers: [
        UsersService,
        UserRepository
    ],

    exports: [UserRepository]
})
export default class UsersModule { }
