import { describe, it, expect } from 'vitest';
import { defineConfig, config } from '@/.';

describe('Example usage', () => {
    it('should define configurations and retrieve them', () => {
        defineConfig({
            example: {
                key: 'example value',
                nested: {
                    key: 'example nested value'
                }
            }
        });

        expect(config('example.key')).toBe('example value');
        expect(config('example.nested.key')).toBe('example nested value');
    });

    it('will return undefined for contents that do not exist', () => {
        defineConfig({ foo: 'bar' });

        expect(config('baz')).toBeUndefined();
    });
});
