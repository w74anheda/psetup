export interface ISession {
    sessions: Session[];
}
export interface Session {
    id: string;
    os: string;
    browser: string;
    ip_address?: string;
    created_at: string;
    expires_at: string;
    current: boolean;
}
