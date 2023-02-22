import { describe, it, expect } from 'vitest';
import ErrorHandler from '@/ErrorHandler';

describe('Error Handler', () => {
    it('set errors', () => {
        const handler = new ErrorHandler();

        expect(handler.all()).toEqual({});

        handler.record({ username: ['Value is required'] });

        expect(handler.all()).toEqual({ username: ['Value is required'] });
    });

    it('can get all errors', () => {
        const allErrors = { username: ['Value is required'] };
        const handler = new ErrorHandler();

        handler.record(allErrors);

        expect(handler.all()).toEqual(allErrors);
    });

    it('can determine if there is an error for a field', () => {
        const handler = new ErrorHandler();

        handler.record({ username: ['Value is required'] });

        expect(handler.has('username')).toBeTruthy();
    });

    it('can determine if there are any errors', () => {
        const handler = new ErrorHandler();

        handler.record({ username: ['Value is required'] });

        expect(handler.any()).toBeTruthy();
    });

    it('can get the first error message for a field', () => {
        const handler = new ErrorHandler();

        handler.record({ username: ['Value is required', 'Value must be unique'] });

        expect(handler.get('username')).toBe('Value is required');
        expect(handler.get('password')).toBeUndefined();
    });

    it('can get all the error message for a field', () => {
        const handler = new ErrorHandler();

        handler.record({ username: ['Value is required', 'Value must be unique'] });

        expect(handler.getAll('username')).toEqual(['Value is required', 'Value must be unique']);
    });

    it('can get all the errors in a flat array', () => {
        const handler = new ErrorHandler();

        handler.record({
            username: ['Username is required'],
            email: ['Email is required', 'Email is not valid']
        });

        expect(handler.flatten()).toEqual([
            'Username is required',
            'Email is required',
            'Email is not valid'
        ]);
    });

    it('can clear one error field', () => {
        const handler = new ErrorHandler();

        handler.record({
            username: ['Value is required'],
            password: ['Value is required']
        });
        handler.clear('username');

        expect(handler.has('username')).toBeFalsy();
        expect(handler.has('password')).toBeTruthy();
    });

    it('can clear all the error fields', () => {
        const handler = new ErrorHandler();

        handler.record({
            username: ['Value is required'],
            password: ['Value is required']
        });
        handler.clear();

        expect(handler.any()).toBeFalsy();
    });
});
