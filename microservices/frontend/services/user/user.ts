import { ApiResponse } from 'models/apiResponse';
import { IUser } from '~~/models/user/user';
import { FetchApi } from '~~/utils/fetchApi';

export const getCurrentUserData = (): Promise<ApiResponse<IUser>> => {
    return FetchApi('/auth/me')
}