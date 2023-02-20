import { ConnectOptions } from 'mongoose';

type Config = {
    uri: string;
    options: ConnectOptions;
};

export default Config;
