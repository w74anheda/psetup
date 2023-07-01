export interface ILoginDTO {
  message: string;
  verification: Verification;
  errors: [];
  status: number;
}

export interface Verification {
  hash: string;
  code: string;
  is_new: boolean;
  expire_at: string;
}