import { Model, Schema } from 'mongoose';
import Connection from '../connection';


export default abstract class Base<T> {
    protected readonly table: string = '';

    constructor (
        protected connection: Connection,
        protected schema: Schema
    ) { }

    public newInstance (): Model<T> {
        return this.connection.model<T>(
            this.constructor.name,
            this.schema,
            this.table
        );
    }
}
