import { ILoginDTO } from "models/auth/login";
import { FetchApi } from "~~/utils/fetchApi";

export const userLogin = (phone: string): Promise<ILoginDTO> => {
  return FetchApi("auth/login/phonenumber/request", {
    method: "POST",
    body: {
      phone,
    },
  });
};
