import { ApiResponse } from 'models/apiResponse';
import { IUser } from '~~/models/user/user';
import { FetchApi } from '~~/utils/fetchApi';

export const getCurrentUserData = (): Promise<ApiResponse<IUser>> => {
    return FetchApi('/auth/me')
}

export const completeUserProfile = (birth_day: string, national_id: string): Promise<ApiResponse<IUser>> => {
    return FetchApi('/auth/complete-profile', {
        method: "PATCH",
        data: {
            birth_day,
            national_id
        }
    })
}