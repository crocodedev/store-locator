import React, {useCallback} from 'react'
import { useNavigate, useLocation} from 'react-router-dom'
import Map, {Marker, NavigationControl} from 'react-map-gl'
import ControlPanel from './ControlPanel'
import {
    Card,
    TextField,
    Page,
    Layout,
    Button,
    FullscreenBar,
    ButtonGroup,
    Select
} from '@shopify/polaris'

import 'mapbox-gl/dist/mapbox-gl.css'

import Pin from './Pin'
import {useAuthenticatedFetch} from "../hooks";

const TOKEN =
	'pk.eyJ1IjoiZGFudmVyeXVyayIsImEiOiJjbGE4MTIzam0wZ2Z3M3ZtcTFlYWc0ZnJ0In0.xOVF1gHOacyuHOe2-LSXZw'

export const MapboxMap = ({
    setMarker,
    value,
    setInfo,
    marker,
    setValue,
    shops,
    setShops,
    options,
    method
}) => {
    const fetch = useAuthenticatedFetch();
    const navigate = useNavigate();
    const handleSelect = useCallback(
        (status) => {
            setValue({...value, status: status})
        },
        [setValue, value]
    )

	const initialViewState = {
		latitude: 51.75717756555855,
		longitude: 19.45560525665522,
		zoom: 3.5
	}

	const onMarkerDrag = useCallback((event) => {
		setMarker({
			longitude: event.viewState.longitude,
			latitude: event.viewState.latitude
		})
	}, [])

	const handleChangeInfo = (text, field) => {
		setValue({...value, [field]: text})
	}

    const handleEditStore = async () => {
        if (
            !Object.values(marker).filter((el) => el === '').length &&
            value.name !== ''
        ) {
            const editShop = {
                ...value,
                longitude: marker.longitude,
                latitude: marker.latitude
            }

            let result = await fetch(`/api/stories/${value.id}`, {
                method: 'PUT',
                body: JSON.stringify(editShop),
                headers: {
                    "Content-Type": "application/json",
                }
            });

            let response = await result.json();

            // navigate(`/store/${response.store.id}`);

            setValue(response.store)
            setMarker({
                latitude: response.store.latitude,
                longitude: response.store.longitude
            })
        }
    }

	const handleAddNewShop = async () => {
		if (
			!Object.values(marker).filter((el) => el === '').length &&
			value.name !== ''
		) {
			const newShop = {
				...marker,
				...value
			}
			setShops([...shops, newShop])

            let result = await fetch("/api/store", {
                method: 'post',
                body: JSON.stringify(newShop),
                headers: {
                    "Content-Type": "application/json",
                }
            });

            let response = await result.json();

            navigate(`/stores/${response.store.id}`);
		}
	}

	return (
        <Page
            fullWidth
            breadcrumbs={[{content: 'MapBox', url: '/stores'}]}
            title={
                method === 'edit' ? value.name : 'New Store'
            }
            primaryAction={{
                content: 'Save',
                onClick:
                    method === 'edit'
                        ? handleEditStore
                        : handleAddNewShop
            }}
            secondaryActions={
                method === 'edit' && [
                    {
                        content: 'Delete',
                        destructive: true
                    }
                ]
            }>
            <Layout>
                <Layout.Section>
                    <Card>
                        <div
                            style={{
                                position: 'relative',
                                width: '100%',
                                height: '600px'
                            }}>
                            <Map
                                dragRotate={false}
                                initialViewState={initialViewState}
                                longitude={marker.longitude}
                                latitude={marker.latitude}
                                mapStyle='mapbox://styles/mapbox/streets-v12'
                                mapboxAccessToken={TOKEN}
                                onDrag={onMarkerDrag}
                                onZoom={onMarkerDrag}>
                                <NavigationControl showCompass={false} />
                            </Map>
                            <div
                                style={{
                                    position: 'absolute',
                                    zIndex: '10',
                                    left: '50%',
                                    top: '50%'
                                }}>
                                <Pin size={20} />
                            </div>
                        </div>
                    </Card>
                </Layout.Section>
                <Layout.Section secondary>
                    <Card title='Coordinates' sectioned>
                        <TextField label='Latitude' value={marker.latitude || 51.75717756555855} />
                        <TextField label='Longitude' value={marker.longitude || 19.45560525665522} />
                    </Card>
                    <Card title='Store info'>
                        <Card.Section title='Status'>
                            <Select
                                options={options}
                                onChange={handleSelect}
                                value={value.status || 'draft'}
                            />
                        </Card.Section>
                        <Card.Section title='Store name'>
                            <TextField
                                name='name'
                                value={value.name || ''}
                                autoComplete='off'
                                onChange={(e) =>
                                    handleChangeInfo(e, 'name')
                                }
                            />
                        </Card.Section>
                    </Card>
                </Layout.Section>
                <Layout.Section sectioned>
                    <ControlPanel
                        value={value}
                        handleChangeInfo={handleChangeInfo}
                    />
                </Layout.Section>
            </Layout>
        </Page>
	)
}
