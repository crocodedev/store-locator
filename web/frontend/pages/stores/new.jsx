import { useState } from 'react';
import { MapboxMap } from "../../components";

export default function NewStore() {
    const [marker, setMarker] = useState({
        latitude: 51.75717756555855,
        longitude: 19.45560525665522
    })
    const options = [
        {label: 'Active', value: 'active'},
        {label: 'Draft', value: 'draft'}
    ]
    const [info, setInfo] = useState()
    const [value, setValue] = useState({
        storeName: '',
        isAvailable: false,
        address_1: '',
        address_2: '',
        city: '',
        postcode: '',
        state: '',
        country: '',
        phone: '',
        fax: '',
        site: '',
        social_instagram: '',
        social_twitter: '',
        social_facebook: '',
        social_tiktok: ''
    })
    let [shops, setShops] = useState([]);

    return (
        <MapboxMap
            marker={marker}
            setMarker={setMarker}
            info={info}
            setInfo={setInfo}
            value={value}
            setValue={setValue}
            shops={shops}
            setShops={setShops}
            options={options}
            method='new'
        />
    );
}
