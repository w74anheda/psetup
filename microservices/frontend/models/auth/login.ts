export interface ILoginDTO {
    message: string;
    verification: Verification;
    errors: [];
  }
  
  export interface Verification {
    hash: string;
    code: string;
    expire_at: string;
}