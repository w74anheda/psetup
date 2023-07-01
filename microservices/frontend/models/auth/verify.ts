export interface IVerify {
    hash: string;
    code: string;
    gender: Gender;
    first_name: string;
    last_name: string;
}

export interface IVerifyDTO {
    access_token: string;
    refresh_token: string;
}

export enum Gender {
    مرد = "male",
    زن = "female",
}
