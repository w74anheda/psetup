export interface IAddress {
    addresses: Address[];
}

export interface Address {
    id: number;
    city: City;
    full_address: string;
    house_number: number;
    unit_number: number;
    postalcode: string;
    point: Point;
}

export interface Point {
    latitude: string;
    longitude: string;
}

export interface City {
    id: number;
    name: string;
    state: State;
}

export interface State {
    id: number;
    name: string;
}