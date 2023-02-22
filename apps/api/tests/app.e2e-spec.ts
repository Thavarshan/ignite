import { Test, TestingModule } from '@nestjs/testing';
import { INestApplication, ValidationPipe } from '@nestjs/common';
import request from 'supertest';
import mongoose from 'mongoose';
import AppModule from '../src/app.module';

describe('AppController (e2e)', () => {
    let server: any;
    let app: INestApplication;

    beforeEach(async () => {
        const moduleFixture: TestingModule = await Test.createTestingModule({
            imports: [AppModule],
        }).compile();

        app = moduleFixture.createNestApplication();
        app.enableShutdownHooks();
        app.useGlobalPipes(new ValidationPipe());
        await app.init();
    });

    afterAll(async () => {
        await app.close();
    });

    it('/ (GET)', async () => {
        return await request(app.getHttpServer())
            .get('/')
            .expect(200);
    });
});
