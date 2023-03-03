/* eslint-disable @typescript-eslint/no-unused-vars */
const virtualMock = jest.fn();

jest.mock('mongoose', () => ({
    Schema: jest.fn().mockImplementation((def: any, options: any) => ({
        constructor: jest.fn(),
        virtual: virtualMock,
        pre: jest.fn(),
        set: jest.fn(),
    })),

    SchemaTypes: jest.fn().mockImplementation(() => ({
        String: jest.fn(),
    })),
}));

import UserSchema, { preSaveHook } from './user.schema';

describe('UserSchema', () => {
    test('should execute next middleware when name or password is not modified', async () => {
        const nextMock = jest.fn();
        const contextMock = { isModified: jest.fn() };

        contextMock.isModified.mockReturnValueOnce(false);
        await preSaveHook.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('name');
        expect(contextMock.isModified).toBeCalledWith('password');
        expect(nextMock).toBeCalledTimes(1);
    });

    test('should set username when name is modified', async () => {
        const nextMock = jest.fn();
        const contextMock = {
            isModified: jest.fn(),
            set: jest.fn(),
            name: 'MyName',
        };

        contextMock.isModified.mockReturnValueOnce(true);
        await preSaveHook.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('name');
        expect(nextMock).toBeCalledTimes(1);
        expect(contextMock.set).toBeCalledTimes(1);
    });

    test('should set password when password is modified', async () => {
        const nextMock = jest.fn();
        const contextMock = {
            isModified: jest.fn(),
            set: jest.fn(),
            password: '123456',
        };

        contextMock.isModified.mockReturnValueOnce(true);
        await preSaveHook.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('password');
        expect(nextMock).toBeCalledTimes(1);
        expect(contextMock.set).toBeCalledTimes(1);
    });

    it('should called Schame.virtual for tickets', () => {
        expect(UserSchema).toBeDefined();

        expect(virtualMock).toBeCalled();
        expect(virtualMock).toHaveBeenNthCalledWith(1, 'tickets', {
            foreignField: 'user',
            localField: '_id',
            ref: 'Ticket',
            justOne: false
        });
        expect(virtualMock).toHaveBeenNthCalledWith(2, 'ticketsAssigned', {
            foreignField: 'agent',
            localField: '_id',
            ref: 'Ticket',
            justOne: false
        });
        expect(virtualMock).toBeCalledTimes(2);
    });
});
