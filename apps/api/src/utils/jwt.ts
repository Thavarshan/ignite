import { BadRequestException } from '@nestjs/common';
import Cryptr from 'cryptr';
import { Request } from 'express';

const extractor = (request: Request): string => {
    let token: string = null;

    if (request.header('x-token')) {
        token = request.get('x-token');
    } else if (request.headers.authorization) {
        token = request.headers
            .authorization
            .replace('Bearer ', '')
            .replace(' ', '');
    } else if (request.body.token) {
        token = request.body.token.replace(' ', '');
    }

    if (request.query.token) {
        token = request.body.token.replace(' ', '');
    }

    const cryptr = new Cryptr('AuX1bmFkYR5eEGSy1Zy41LT8oW1BF64MrE1X5N8zSY');

    if (token) {
        try {
            token = cryptr.decrypt(token);
        } catch (err) {
            throw new BadRequestException('Bad request.');
        }
    }

    return token;
};

export default extractor;
