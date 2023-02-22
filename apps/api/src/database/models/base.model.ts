import { Model, Schema } from 'mongoose';
import DB from '@/database/enums/db.enum';
import Connection from '@/database/connection';
import { Inject, Injectable } from '@nestjs/common';

@Injectable()
export default abstract class Base<T> {
    /**
     * The table associated with the model.
     *
     * @var {string}
     */
    protected readonly table: string = '';

    /**
     * Create a new Model instance.
     *
     * @param {Connection} connection
     * @param {Schema} schema
     *
     * @return  {void}
     */
    constructor (
        @Inject(DB.KEY) protected connection: Connection,
        protected schema: Schema
    ) { }

    /**
     * Initialize a usable state of the model.
     *
     * @return  {Model<T>}
     */
    public newInstance (): Model<T> {
        return this.connection.model<T>(
            this.constructor.name,
            this.schema,
            this.table
        );
    }
}
