import Axios from 'axios';
import { config } from '@ignite/scribe';

const axios = Axios.create({
    baseURL: config('apiUrl'),
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    withCredentials: true
});

export default axios;
