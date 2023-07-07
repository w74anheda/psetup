import { FetchApi } from "~~/utils/fetchApi";
import { IVerify, IVerifyDTO } from "~/models/auth/verify";
import { ApiResponse } from "models/apiResponse";

export const userVerify = (command: IVerify): Promise<ApiResponse<IVerifyDTO>> => {
    return FetchApi("auth/login/phonenumber/verify", {
        method: "POST",
        data: command
    });
};
