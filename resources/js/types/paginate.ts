export interface Paginated<T> {
    data: T[];
    links: {
        first: string | null;
        last: string | null;
        next: string | null;
        prev: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        links: {
            active: boolean;
            label: string;
            url: string | null;
        }[];
        path: string;
        per_page: number;
        to: number;
        total: number;
    };
}

export interface Pagination<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    links: PaginationLinks;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
}

export type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

export type PaginationLinks = PaginationLink[];
