import { Module } from '@nestjs/common';
import AuthService from './services/auth.service';
import UsersModule from '@/users/users.module';
import AuthController from './controllers/auth.controller';
import { MongooseModule } from '@nestjs/mongoose';
import UserSchema from '@/users/schemas/user.schema';
import UserRepository from '@/users/repositories/user.repository';
import LocalAuth from './authenticators/local.auth';
import TokenAuth from './authenticators/token.auth';
import { JwtModule } from '@nestjs/jwt';
import { ConfigModule } from '@nestjs/config';
import { PassportModule } from '@nestjs/passport';
import JwtStrategy from '@/auth/strategies/jwt.strategy';
import LocalStrategy from '@/auth/strategies/local.strategy';

@Module({
    imports: [
        ConfigModule,
        UsersModule,
        PassportModule,
        MongooseModule.forFeature([
            {
                name: 'User',
                schema: UserSchema
            }
        ]),
        JwtModule.register({
            secret: process.env.JWT_SECRET,
            signOptions: { expiresIn: '30m' },
        }),
    ],

    controllers: [AuthController],

    providers: [
        LocalAuth,
        TokenAuth,
        AuthService,
        UserRepository,
        JwtStrategy,
        LocalStrategy
    ]
})
export default class AuthModule { }
