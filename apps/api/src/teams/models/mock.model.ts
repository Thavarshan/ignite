class Team {
    constructor (
        public name: string,
        public email: string,
        public slug: string,
        public description?: string,
        public phone?: string
    ) { }

    public async save (): Promise<void> {
        //
    }
}

const team = new Team(
    'Wade\'s team',
    'ws@wxample.com',
    'wades-team',
    'This is Wades MFuggin Team!',
    '1234567890'
);

export default team;
