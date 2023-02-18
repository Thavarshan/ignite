import { NestFactory } from '@nestjs/core';
import AppModule from './app.module';
import { ValidationPipe } from '@nestjs/common';
import { info } from 'console';

async function bootstrap () {
    const app = await NestFactory.create(AppModule);

    app.useGlobalPipes(new ValidationPipe({
        // disableErrorMessages: true,
    }));

    const PORT = process.env.PORT || 3000;

    await app.listen(PORT);

    info(`APP IS LISTENING TO PORT ${PORT}`);
}

bootstrap();
