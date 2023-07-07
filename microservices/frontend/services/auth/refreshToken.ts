import { ApiResponse } from "models/apiResponse";
import { IVerifyDTO } from "~~/models/auth/verify";
import { FetchApi } from "~~/utils/fetchApi";

export const refreshToken = (refresh_token: string): Promise<ApiResponse<IVerifyDTO>> => {
    return FetchApi("/auth/refreshAccessToken", {
        method: "POST",
        data: {
            refresh_token
        }
    });
};
