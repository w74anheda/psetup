import { IAddress } from '~~/models/address';
import { ApiResponse } from '~~/models/apiResponse';
import { FetchApi } from '~~/utils/fetchApi';

export const getAddresses = (): Promise<ApiResponse<IAddress>> => {
    return FetchApi("address");
}