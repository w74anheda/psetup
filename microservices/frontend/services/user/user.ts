import { IUser } from '~~/models/user/user';
import { FetchApi } from '~~/utils/fetchApi';

export const getCurrentUserData = (): Promise<IUser> => {
    return FetchApi('/auth/me')
}