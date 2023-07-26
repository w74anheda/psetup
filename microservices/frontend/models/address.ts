export interface IAddress {
    id: number;
    user_id: string;
    city_id: number;
    full_address: string;
    house_number: number;
    unit_number: number;
    postalcode: string;
    latitude: string;
    longitude: string;
    city: City;
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