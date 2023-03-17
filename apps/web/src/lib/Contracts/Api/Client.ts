export default interface Client {
    resources (_name: string): Client;
    all (): Promise<any[]>;
    get (_id: string): Promise<any>;
    create (_data: { [key: string]: any; }): Promise<any>;
}
