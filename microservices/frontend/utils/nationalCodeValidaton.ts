export const validateNationalId = (national_id: string) => {
    national_id = String(national_id);
    if (national_id.length !== 10) return false;
    var check = parseInt(national_id[9]);
    var sum = 0;
    for (var i = 0; i < 9; i++) {
        sum += parseInt(national_id[i]) * (10 - i);
    }
    var remainder = sum % 11;
    if ((remainder < 2 && check === remainder) || (remainder >= 2 && check === 11 - remainder)) return true;
    return false;
}
