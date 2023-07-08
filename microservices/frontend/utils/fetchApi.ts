import { useLoading } from "~~/store/base/loading";
import { refreshToken } from "~~/services/auth/refreshToken";
import { useAuth } from "~~/store/userAuth";
import axios from "axios";

export const FetchApi = axios.create();
// Request interceptor for API calls
FetchApi.interceptors.request.use(
    async (config) => {
        useLoading().handleLoading(true);
        config.baseURL = "http://localhost:8040/api/v1/";
        //@ts-ignore
        config.headers = {
            Authorization: `Bearer ${useAuth().verifyResult?.access_token}`,
            Accept: "application/json",
            "User-Agent":
                "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.114 Safari/537.36 RuxitSynthetic/1.0 v4954752706012875637 t7725840419877098505 ath93eb305d altpriv cvcv=2 cexpw=1 smf=0",
        };
        return config;
    },
    (error) => {
        Promise.reject(error);
    }
);
// Response interceptor for API calls
FetchApi.interceptors.response.use(
    (response) => {
        useLoading().handleLoading(false);
        return response;
    },
    async function (error) {
        const originalRequest = error.config;
        if (error.response.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;
            const access_token = await refreshToken(
                useAuth().verifyResult!.refresh_token
            );
            if (access_token.status === 200) {
                useAuth().verifyResult = access_token.data;
                axios.defaults.headers.common["Authorization"] = "Bearer " + access_token.data.access_token;
                return FetchApi(originalRequest);
            }
        }
        return Promise.reject(error);
    }
);
