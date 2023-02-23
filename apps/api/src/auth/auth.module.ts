import { Module } from '@nestjs/common';
import AuthService from './services/auth.service';
import UsersModule from '@/users/users.module';
import AuthController from './controllers/auth.controller';
import UserRepository from '@/users/repositories/user.repository';
import LocalAuth from './authenticators/local.auth';
import TokenAuth from './authenticators/token.auth';
import { JwtModule, JwtModuleOptions } from '@nestjs/jwt';
import { ConfigModule, ConfigType } from '@nestjs/config';
import { PassportModule } from '@nestjs/passport';
import JwtStrategy from '@/auth/strategies/jwt.strategy';
import LocalStrategy from '@/auth/strategies/local.strategy';
import authConfig from '@/config/auth.config';

@Module({
    imports: [
        UsersModule,
        PassportModule,
        ConfigModule.forFeature(authConfig),
        JwtModule.registerAsync({
            inject: [authConfig.KEY],
            imports: [ConfigModule.forFeature(authConfig)],
            useFactory: (config: ConfigType<typeof authConfig>) => {
                return {
                    secret: config.secretKey,
                    signOptions: { expiresIn: config.expiresIn },
                } as JwtModuleOptions;
            }
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
