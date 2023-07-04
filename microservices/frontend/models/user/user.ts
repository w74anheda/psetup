export interface IUser {
    id: number;
    first_name?: any;
    last_name?: any;
    gender?: any;
    phone: string;
    is_active: boolean;
    last_online_at: string;
    email?: any;
    email_verified_at?: any;
    activated_at: string;
    personal_info: PersonalInfo;
    created_at: string;
    updated_at: string;
    is_new: boolean;
    status: number;
}

export interface PersonalInfo {
    is_completed: boolean;
    birth_day?: any;
    national_id?: any;
}