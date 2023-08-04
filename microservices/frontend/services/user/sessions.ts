import { ApiResponse } from 'models/apiResponse';
import { FetchApi } from './../../utils/fetchApi';
import { ISession } from 'models/user/sessions';

export const getAllSessions = (): Promise<ApiResponse<ISession>> => {
    return FetchApi('auth/sessions')
}