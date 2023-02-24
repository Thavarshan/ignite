/* eslint-disable @typescript-eslint/no-unused-vars */
const virtualMock = jest.fn().mockImplementationOnce((name: string) => name);

import UserSchema, { preSaveHookPassword, preSaveHookUsername } from './user.schema';

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

describe('UserSchema', () => {
    it('should called Schame.virtual ', () => {
        expect(UserSchema).toBeDefined();
        expect(virtualMock).toBeCalled();
        expect(virtualMock).toHaveBeenNthCalledWith(1, 'posts', {
            foreignField: 'userId',
            localField: '_id',
            ref: 'Post',
        });
        expect(virtualMock).toBeCalledTimes(1);
    });

    test('should execute next middleware when name is not modified', async () => {
        const nextMock = jest.fn();
        const contextMock = { isModified: jest.fn() };

        contextMock.isModified.mockReturnValueOnce(false);
        await preSaveHookUsername.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('name');
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
        await preSaveHookUsername.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('name');
        expect(nextMock).toBeCalledTimes(1);
        expect(contextMock.set).toBeCalledTimes(1);
    });

    test('should execute next middleware when password is not modified', async () => {
        const nextMock = jest.fn();
        const contextMock = { isModified: jest.fn() };

        contextMock.isModified.mockReturnValueOnce(false);
        await preSaveHookPassword.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('password');
        expect(nextMock).toBeCalledTimes(1);
    });

    test('should set password when password is modified', async () => {
        const nextMock = jest.fn();
        const contextMock = {
            isModified: jest.fn(),
            set: jest.fn(),
            password: '123456',
        };

        contextMock.isModified.mockReturnValueOnce(true);
        await preSaveHookPassword.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('password');
        expect(nextMock).toBeCalledTimes(1);
        expect(contextMock.set).toBeCalledTimes(1);
    });
});
