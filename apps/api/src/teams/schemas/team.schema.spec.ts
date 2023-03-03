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

import TeamSchema, { preSaveHook } from './team.schema';

describe('TeamSchema', () => {
    it('should called Schame.virtual ', () => {
        expect(TeamSchema).toBeDefined();
        expect(virtualMock).toBeCalled();
        expect(virtualMock).toHaveBeenNthCalledWith(1, 'members', {
            foreignField: 'team',
            localField: '_id',
            ref: 'User',
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
            name: 'TeamName',
        };

        contextMock.isModified.mockReturnValueOnce(true);
        await preSaveHook.call(contextMock, nextMock);

        expect(contextMock.isModified).toBeCalledWith('name');
        expect(nextMock).toBeCalledTimes(1);
        expect(contextMock.set).toBeCalledTimes(1);
    });
});
