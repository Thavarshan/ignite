import LoggerModule from '@/logger/logger.module';
import ConfigModule from '@/config/config.module';
import dbConfig from '@/config/database.config';
import { Module } from '@nestjs/common';

@Module({
    imports: [
        LoggerModule,
        ConfigModule.forFeature(dbConfig)
    ]
})
export default class DatabaseModule { }
