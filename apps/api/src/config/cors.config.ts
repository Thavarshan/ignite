const whitelist = [
    'http://localhost:3000',
    process.env.WEB_URL
];

const corsConfig = {
    origin: (origin: any, callback: any) => {
        if (whitelist.indexOf(origin) !== -1) {
            console.log('allowed cors for:', origin);
            callback(null, true);
        } else {
            console.log('blocked cors for:', origin);
            callback(new Error('Not allowed by CORS'));
        }
    },
    allowedHeaders: 'X-Requested-With, X-HTTP-Method-Override, Content-Type, Accept, Observe, Authorization',
    methods: 'GET,PUT,POST,DELETE,UPDATE,OPTIONS',
    credentials: true,
};

export default corsConfig;
