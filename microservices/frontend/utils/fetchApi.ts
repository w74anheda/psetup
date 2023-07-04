import { FetchOptions, FetchError, ofetch } from "ofetch";
import { useAuth } from "~~/store/userAuth";
import { useLoading } from "~~/store/base/loading";
import { refreshToken } from "~~/services/auth/refreshToken";

export const FetchApi = async (url: string, config: FetchOptions = {}) => {
    let status: number;
    const userToken = useAuth();
    config = {
        baseURL: "http://localhost:8040/api/v1/",
        headers: {
            Accept: "application/json",
            "User-Agent":
                "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.114 Safari/537.36 RuxitSynthetic/1.0 v4954752706012875637 t7725840419877098505 ath93eb305d altpriv cvcv=2 cexpw=1 smf=0",
        },
        ...config,
        async onRequest() {
            useLoading().handleLoading(true);
        },
        async onResponse({ response }) {
            status = response.status;
            useLoading().handleLoading(false);
            if (response.status === 401) {
                const newAccessToken = await refreshToken(
                    loginData!.refresh_token.toString()
                );
                if (newAccessToken.status === 200) {
                    //@ts-ignore
                    config.headers["Authorization"] = `Bearer ${newAccessToken.access_token}`;
                    localStorage.setItem(
                        "auth",
                        JSON.stringify({
                            access_token: newAccessToken.access_token,
                            refresh_token: newAccessToken.refresh_token,
                            expires_in: newAccessToken.expires_in,
                        })
                    );
                }
            }
        },
    };
    if (!config.headers) {
        config.headers = {};
    }
    if (userToken && userToken.isLogin) {
        var loginData = userToken.verifyResult;
        //@ts-ignore
        config.headers["Authorization"] = `Bearer ${loginData?.access_token}`;
    }
    return ofetch(url, config)
        .then((data) => {
            data["status"] = status;
            return data;
        })
        .catch((e: FetchError) => {
            return e.response?._data ?? "مشکلی در عملیات رخ داده است.";
        });
};
