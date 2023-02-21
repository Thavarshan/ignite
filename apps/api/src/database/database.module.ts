import LoggerModule from '@/logger/logger.module';
import ConfigModule from '@/config/config.module';
import dbConfig from '@/config/database.config';
import { Module } from '@nestjs/common';
import ConnectionProvider from './providers/connection.provider';

@Module({
    imports: [
        LoggerModule,
        ConfigModule.forFeature(dbConfig)
    ],

    providers: [...ConnectionProvider],

    exports: [...ConnectionProvider],
})
export default class DatabaseModule { }
