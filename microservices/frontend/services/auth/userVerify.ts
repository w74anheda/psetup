import { FetchApi } from "~~/utils/fetchApi";
import { IVerify, IVerifyDTO } from "~/models/auth/verify";

export const userVerify = (command: IVerify): Promise<IVerifyDTO> => {
    return FetchApi("auth/login/phonenumber/verify", {
        method: "POST",
        body: command
    });
};
