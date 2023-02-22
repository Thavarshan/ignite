import { describe, it, expect } from 'vitest';
import Repository from '@/Repository';

describe('Form', () => {
    it('can be instantiated with properties', () => {
        const repository = new Repository({
            foo: 'bar'
        });

        expect(repository.get('foo')).toBe('bar');
    });

    it('can get all items', () => {
        const repository = new Repository({
            foo: 'bar'
        });

        expect(repository.all()).toMatchObject({ foo: 'bar' });
    });

    it('can set new items', () => {
        const repository = new Repository({
            foo: 'bar'
        });

        expect(repository.all()).toMatchObject({ foo: 'bar' });

        repository.set('bar', 'baz');

        expect(repository.all()).toMatchObject({
            foo: 'bar',
            bar: 'baz'
        });
    });

    it('can overwrite existing items', () => {
        const repository = new Repository({
            foo: 'bar'
        });

        expect(repository.all()).toMatchObject({ foo: 'bar' });

        repository.set('foo', 'baz');

        expect(repository.all()).toMatchObject({
            foo: 'baz'
        });
    });
});
