import { NestFactory } from '@nestjs/core';
import AppModule from './app.module';
import { ValidationPipe } from '@nestjs/common';
import { info } from 'console';
import LoggerService from '@/logger/services/logger.service';

async function bootstrap () {
    const app = await NestFactory.create(AppModule);

    app.enableShutdownHooks();

    app.useGlobalPipes(new ValidationPipe({
        // disableErrorMessages: true,
    }));

    app.enableCors();
    app.useLogger(new LoggerService());

    const PORT = process.env.PORT || 3001;

    await app.listen(PORT);

    info(`App URL accessible on http://localhost:${PORT}`);
}

bootstrap();
