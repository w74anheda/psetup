export interface IUser {
    id: string;
    first_name: string;
    last_name: string;
    gender: string;
    phone: string;
    email?: any;
    personal_info: Personalinfo;
    is_active: boolean;
    created_at: string;
    permissions: any[];
  }
  
  export interface Personalinfo {
    is_completed: boolean;
    birth_day?: any;
    national_id?: any;
  }