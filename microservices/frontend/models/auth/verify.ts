export interface IVerify {
    hash: string;
    code: string;
    gender: Gender;
    first_name: string;
    last_name: string;
}

export enum Gender {
    مرد,
    زن
}
