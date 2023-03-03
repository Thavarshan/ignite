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

import TicketSchema, { preSaveHook } from './ticket.schema';

describe('TicketSchema', () => {
    it('should call Schame.virtual ', () => {
        expect(TicketSchema).toBeDefined();
        expect(virtualMock).toBeCalled();
        expect(virtualMock).toHaveBeenNthCalledWith(1, 'replies', {
            foreignField: 'ticket',
            localField: '_id',
            ref: 'Reply',
            justOne: false
        });
        expect(virtualMock).toBeCalledTimes(1);
    });

    test('should execute next middleware when title is not modified', async () => {
        const nextMock = jest.fn();
        const contextMock = { isModified: jest.fn() };

        contextMock.isModified.mockReturnValueOnce(false);
        await preSaveHook.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('title');
        expect(nextMock).toBeCalledTimes(1);
    });

    test('should set slug when title is modified', async () => {
        const nextMock = jest.fn();
        const contextMock = {
            isModified: jest.fn(),
            set: jest.fn(),
            title: 'Problem Title',
        };

        contextMock.isModified.mockReturnValueOnce(true);
        await preSaveHook.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('title');
        expect(nextMock).toBeCalledTimes(1);
        expect(contextMock.set).toBeCalledTimes(1);
    });
});
