/**
 * Call the given Closure with the given value then return the value.
 *
 * @param   {any}  value
 * @param   {function|undefined}  callback
 *
 * @return  {any}
 */
export function tap (
    value: any,
    callback?: (_value: any) => any
): any {
    if (callback === undefined) {
        return value;
    }

    const result = callback(value);

    if (result !== undefined) {
        return result;
    }

    return value;
}
