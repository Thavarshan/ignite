type Config = {
    method: string,
    queries?: { [key: string]: string; };
    params?: { [key: string]: string; };
    headers: { [key: string]: string; };
    body?: string;
};

export default Config;
