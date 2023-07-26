import { City, IAddress, State } from '~~/models/address';
import { ApiResponse } from '~~/models/apiResponse';
import { FetchApi } from '~~/utils/fetchApi';

export const getAddresses = (): Promise<ApiResponse<IAddress>> => {
    return FetchApi("address");
}

export const getStates = (): Promise<ApiResponse<State>> => {
    return FetchApi("state");
}

export const getCities = (): Promise<ApiResponse<City>> => {
return FetchApi("city");
}

// export const addAddrress = () => {
    
// }