export interface Option {
    label: string;
    value: string;
}

export interface ContentGroupOption extends Option {
    image?: string;
    description?: string;
    payload?: unknown;
}
