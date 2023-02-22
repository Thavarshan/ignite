import { describe, it, expect } from 'vitest';
import MockAdapter from 'axios-mock-adapter';
import axios from 'axios';
import HttpHandler from '@/HttpHandler';

describe('HTTP Handler', () => {
    it('can handle get requests', async () => {
        const mockAdapter = new MockAdapter(axios);

        mockAdapter.onGet('/users').reply(200, {
            users: ['John Doe', 'Jane Doe']
        });

        const http = (new HttpHandler()).createInstance();

        await http.get('/users').then((response) => {
            expect(response.status).toBe(200);
            expect(response.data.users).toEqual(['John Doe', 'Jane Doe']);
        });
    });

    it('can handle error responses', async () => {
        const mockAdapter = new MockAdapter(axios);

        mockAdapter.onGet('/users').reply(422, {
            errors: {
                username: ['This field is required.']
            }
        });

        const http = (new HttpHandler()).createInstance();

        await http.get('/users')
            .then(() => {
                // eslint-disable-next-line no-console
                console.log('Failed to capture error');
            })
            .catch((error) => {
                expect(error.response.status).toBe(422);
            });
    });

    it('can set custom configurations', () => {
        const http = new HttpHandler();

        const client = http.acceptJson()
            .contentType('application/json')
            .baseUrl('https://api.cratespace.xyz/')
            .setToken('123456')
            .createInstance();

        expect(client.defaults.headers.Authorization).toBe('Bearer 123456');
        expect(client.defaults.headers.Accept).toBe('application/json');
        expect(client.defaults.headers['Content-Type']).toBe('application/json');
        expect(client.defaults.baseUrl).toBe('https://api.cratespace.xyz/');
    });
});
