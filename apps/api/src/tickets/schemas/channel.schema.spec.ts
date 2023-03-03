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

import ChannelSchema, { preSaveHook } from './channel.schema';

describe('ChannelSchema', () => {
    it('should call Schame.virtual ', () => {
        expect(ChannelSchema).toBeDefined();
        expect(virtualMock).toBeCalled();
        expect(virtualMock).toHaveBeenNthCalledWith(1, 'tickets', {
            foreignField: 'channel',
            localField: '_id',
            ref: 'Ticket',
            justOne: false
        });
        expect(virtualMock).toBeCalledTimes(1);
    });

    test('should execute next middleware when name is not modified', async () => {
        const nextMock = jest.fn();
        const contextMock = { isModified: jest.fn() };

        contextMock.isModified.mockReturnValueOnce(false);
        await preSaveHook.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('name');
        expect(nextMock).toBeCalledTimes(1);
    });

    test('should set slug when name is modified', async () => {
        const nextMock = jest.fn();
        const contextMock = {
            isModified: jest.fn(),
            set: jest.fn(),
            name: 'ChannelName',
        };

        contextMock.isModified.mockReturnValueOnce(true);
        await preSaveHook.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('name');
        expect(nextMock).toBeCalledTimes(1);
        expect(contextMock.set).toBeCalledTimes(1);
    });
});
