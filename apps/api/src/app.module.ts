import { Module } from '@nestjs/common';
import UsersModule from './users/users.module';
import AppController from './app.controller';
import AppService from './app.service';
import { MongooseModule } from '@nestjs/mongoose';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { AuthModule } from './auth/auth.module';

@Module({
    imports: [
        ConfigModule.forRoot(),
        MongooseModule.forRootAsync({
            imports: [ConfigModule],
            useFactory: async (config: ConfigService) => ({
                uri: 'mongodb+srv://ignite:alphaxion77@cluster0.kefpsyw.mongodb.net/?retryWrites=true&w=majority',
            }),
            inject: [ConfigService],
        }),
        UsersModule,
        AuthModule
    ],

    controllers: [AppController],

    providers: [AppService],
})
export default class AppModule { }
