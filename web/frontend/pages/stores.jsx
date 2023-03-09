import React, {useCallback, useState} from 'react';
import { useNavigate} from 'react-router-dom'
import { ShopList } from "../components";
import { useAppQuery } from "../hooks";

export default function Store() {
    const navigate = useNavigate();

    const [marker, setMarker] = useState({
        latitude: 51.75717756555855,
        longitude: 19.45560525665522
    })
    const [textFieldValue, setTextFieldValue] = useState('')
    const [info, setInfo] = useState()
    const [value, setValue] = useState({
        storeName: '',
        isAvailable: false,
        street: '',
        apartments: '',
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
    const [shops, setShops] = useState([]);

    const [loading, setLoading] = useState(true)

    const handleTextFieldChange = useCallback((value) => {
        setTextFieldValue(value);
    }, [])

    const {
        data,
        refetch: refetchStories,
    } = useAppQuery({
        url: `/api/store?q=${textFieldValue}`,
        reactQueryOptions: {
            onSuccess: (data) => {
                setLoading(false)
                setShops(data.stories);
            },
        },
    });

    const handleClearButtonClick = useCallback(() => {
        setTextFieldValue('')
    }, [textFieldValue])

    return (
        <ShopList shops={shops} refetchStories={refetchStories} setValue={setValue} loading={loading} setLoading={setLoading} textFieldValue={textFieldValue} handleTextFieldChange={handleTextFieldChange} handleClearButtonClick={handleClearButtonClick} />
    );
}
