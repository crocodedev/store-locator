import {useEffect, useState} from 'react';
import {MapboxMap} from "../../components";
import {useAppQuery} from "../../hooks";

export default function EditStore() {

    const storeId = location.pathname.match(/\/stores\/([0-9]+)/m)[1];

    useAppQuery({
        url: `/api/store/${storeId}`,
        reactQueryOptions: {
            onSuccess: (data) => {
                setMarker({
                    latitude: data.store.latitude,
                    longitude: data.store.longitude
                })

                setValue(data.store);
            },
        },
    });

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
        name: '',
        status: 'draft',
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

    return (
        <MapboxMap
            marker={marker}
            setMarker={setMarker}
            info={info}
            setInfo={setInfo}
            value={value}
            setValue={setValue}
            options={options}
            method='edit'
        />
    );
}
