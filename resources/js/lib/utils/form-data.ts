export function objectToFormData<T>(obj: T): FormData {
    const formData = new FormData();

    for (const key in obj) {
        if (Array.isArray(obj[key])) {
            // eslint-disable-next-line @typescript-eslint/no-explicit-any
            for (const item of obj[key] as any[]) {
                formData.append(key, item);
            }

            continue;
        }

        if (typeof obj[key] === 'object' && !(obj[key] instanceof File)) {
            for (const subKey in obj[key]) {
                formData.append(`${key}.${subKey}`, obj[key][subKey] as string);
            }

            continue;
        }

        if (obj[key] instanceof File) {
            formData.append(key, obj[key] as string | Blob);
            continue;
        }

        formData.append(key, (obj[key] as string | number).toString());
    }

    return formData;
}
