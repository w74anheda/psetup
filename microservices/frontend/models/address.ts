export interface IAddress {
    id: number;
    user_id: number;
    city: City;
}
export interface IAddressDTO extends IAddress {
    city_id: number;
    full_address: string;
    house_number: number;
    unit_number: number;
    postalcode: string;
    latitude: string;
    longitude: string;
}
export interface City {
    id: number;
    state_id: number;
    name: string;
    state: State;
}

export interface State {
    id: number;
    name: string;
}
