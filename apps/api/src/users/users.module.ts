import { Module } from '@nestjs/common';
import UsersService from '@/users/services/users.service';
import UsersController from '@/users/controllers/users.controller';
import UsersRepository from '@/users/repositories/users.repository';
import UsersProvider from './providers/users.provider';
import DatabaseModule from '@/database/database.module';

@Module({
    imports: [
        DatabaseModule
    ],

    controllers: [UsersController],

    providers: [
        UsersService,
        UsersRepository,
        ...UsersProvider
    ],

    exports: [
        UsersService,
        UsersRepository,
        ...UsersProvider
    ]
})
export default class UsersModule { }
