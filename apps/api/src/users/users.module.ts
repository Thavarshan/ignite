import { Module } from '@nestjs/common';
import UsersService from '@/users/services/user.service';
import UsersController from '@/users/controllers/users.controller';
import UserRepository from '@/users/repositories/user.repository';
import { MongooseModule } from '@nestjs/mongoose';
import UsersProvider from './providers/users.provider';
import DatabaseModule from '@/database/database.module';

@Module({
    imports: [
        DatabaseModule
    ],

    controllers: [UsersController],

    providers: [
        UsersService,
        UserRepository,
        UsersProvider,
    ],

    exports: [UserRepository, UsersProvider]
})
export default class UsersModule { }
