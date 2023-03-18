import { Module } from '@nestjs/common';
import AuthService from './services/auth.service';
import UsersModule from '@/users/users.module';
import AuthController from './controllers/auth.controller';
import LocalAuth from './authenticators/local.auth';
import TokenAuth from './authenticators/token.auth';
import { JwtModule, JwtModuleOptions } from '@nestjs/jwt';
import { ConfigModule, ConfigType } from '@nestjs/config';
import { PassportModule } from '@nestjs/passport';
import JwtStrategy from '@/auth/strategies/jwt.strategy';
import LocalStrategy from '@/auth/strategies/local.strategy';
import authConfig from '@/config/auth.config';
import RegisterController from './controllers/register.controller';
import TeamsModule from '@/teams/teams.module';
import PasswordController from './controllers/password.controller';
import PasswordService from './services/password.service';
import authProvider from './providers/auth.provider';
import DatabaseModule from '@/database/database.module';
import RegisterService from './services/register.service';
import UsersService from '@/users/services/users.service';
import TeamsService from '@/teams/services/teams.service';

@Module({
    imports: [
        UsersModule,
        TeamsModule,
        DatabaseModule,
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

    controllers: [
        AuthController,
        RegisterController,
        PasswordController
    ],

    providers: [
        LocalAuth,
        TokenAuth,
        AuthService,
        RegisterService,
        PasswordService,
        UsersService,
        TeamsService,
        JwtStrategy,
        LocalStrategy,
        ...authProvider
    ]
})
export default class AuthModule { }
