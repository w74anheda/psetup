import { FetchOptions, FetchError, ofetch } from "ofetch";
import { useLoading } from '~~/store/base/loading';

export const FetchApi = async (
    url: string,
    config: FetchOptions = {},
) => {
    let status: number;
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
        },
    };
    //@ts-ignore
    return ofetch(url, config)
        .then((data) => {
            data['status'] = status;
            return data;

        })
        .catch((e: FetchError) => {
            return e.response?._data ?? "مشکلی در عملیات رخ داده است.";
        });
};
