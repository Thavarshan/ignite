export interface Session {
    id: string;
    agent: Agent;
    ip_address: string;
    last_active: string;
    is_current_device?: boolean;
}

interface Agent {
    is_desktop: boolean;
    platform: string;
    browser: string;
}
