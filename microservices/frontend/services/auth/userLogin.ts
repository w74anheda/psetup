import { ApiResponse } from "models/apiResponse";
import { ILoginDTO } from "models/auth/login";
import { FetchApi } from "~~/utils/fetchApi";

export const userLogin = (phone: string): Promise<ApiResponse<ILoginDTO>> => {
  return FetchApi("auth/login/phonenumber/request", {
    method: "POST",
    data: {
      phone,
    },
  });
};
