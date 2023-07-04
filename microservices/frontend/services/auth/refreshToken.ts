import { IVerifyDTO } from "~~/models/auth/verify";
import { FetchApi } from "~~/utils/fetchApi";

export const refreshToken = (refresh_token: string): Promise<IVerifyDTO> => {
    return FetchApi("/auth/refreshAccessToken", {
        method: "POST",
        body: {
            refresh_token
        }
    });
};
