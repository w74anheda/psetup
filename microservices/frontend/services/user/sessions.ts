import { ApiResponse } from "models/apiResponse";
import { FetchApi } from "./../../utils/fetchApi";
import { ISession } from "models/user/sessions";

export const getAllSessions = (): Promise<ApiResponse<ISession>> => {
    return FetchApi("auth/sessions");
};

export const revokeSession = (id: string): Promise<ApiResponse<undefined>> => {
    return FetchApi("auth/sessions/revoke/" + id, {
        method: "POST",
    });
};

export const revokeAllSessions = (): Promise<ApiResponse<undefined>> => {
    return FetchApi("auth/sessions/revoke/all", {
        method: "POST",
    });
};