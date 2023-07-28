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

export const addAddress = (command: IAddress): Promise<ApiResponse<IAddress>> => {
    return FetchApi("address", {
        method: "POST",
        data: command
    })
}

export const editAddress = (addressId: number, command: IAddress): Promise<ApiResponse<undefined>> => {
    return FetchApi("address/" + addressId, {
        method: "PATCH",
        data: command
    })
}

export const deleteAddress = (addressId: number): Promise<ApiResponse<undefined>> => {
    return FetchApi("address/" + addressId, {
        method: "DELETE",
    })
}