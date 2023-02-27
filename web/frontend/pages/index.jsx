import React, {useRef, useEffect, useState} from 'react';
import {
    Card,
    Page,
    Layout,
} from "@shopify/polaris";
import {TitleBar} from "@shopify/app-bridge-react";

import '../assets/main.css';
import mapboxgl from 'mapbox-gl/dist/mapbox-gl';

mapboxgl.accessToken = 'pk.eyJ1IjoiZGFudmVyeXVyayIsImEiOiJjbGE4MTIzam0wZ2Z3M3ZtcTFlYWc0ZnJ0In0.xOVF1gHOacyuHOe2-LSXZw';

export default function HomePage() {
    const mapContainer = useRef(null);
    const map = useRef(null);
    const [lng, setLng] = useState(-70.9);
    const [lat, setLat] = useState(42.35);
    const [zoom, setZoom] = useState(9);

    useEffect(() => {
        if (map.current) return; // initialize map only once
        map.current = new mapboxgl.Map({
            container: mapContainer.current,
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [lng, lat],
            zoom: zoom
        });
    });

    useEffect(() => {
        if (!map.current) return; // wait for map to initialize
        map.current.on('move', () => {
            setLng(map.current.getCenter().lng.toFixed(4));
            setLat(map.current.getCenter().lat.toFixed(4));
            setZoom(map.current.getZoom().toFixed(2));
        });
    });

    useEffect(() => {
        if (!map.current) return; // wait for map to initialize
        map.current.on('load', () => {
            map.current.addSource('earthquakes', {
                type: 'geojson',
                data: 'https://cro.codes/test1.geojson',
                cluster: true,
                clusterMaxZoom: 14, // Max zoom to cluster points on
                clusterRadius: 50 // Radius of each cluster when clustering points (defaults to 50)
            });

            map.current.addLayer({
                id: 'clusters',
                type: 'circle',
                source: 'earthquakes',
                filter: ['has', 'point_count'],
                paint: {
                    'circle-color': [
                        'step',
                        ['get', 'point_count'],
                        '#51bbd6',
                        100,
                        '#f1f075',
                        750,
                        '#f28cb1'
                    ],
                    'circle-radius': [
                        'step',
                        ['get', 'point_count'],
                        20,
                        100,
                        30,
                        750,
                        40
                    ]
                }
            });

            map.current.addLayer({
                id: 'cluster-count',
                type: 'symbol',
                source: 'earthquakes',
                filter: ['has', 'point_count'],
                layout: {
                    'text-field': ['get', 'point_count_abbreviated'],
                    'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                    'text-size': 12
                }
            });

            map.current.addLayer({
                id: 'unclustered-point',
                type: 'circle',
                source: 'earthquakes',
                filter: ['!', ['has', 'point_count']],
                paint: {
                    'circle-color': '#11b4da',
                    'circle-radius': 4,
                    'circle-stroke-width': 1,
                    'circle-stroke-color': '#fff'
                }
            });

        });
    });

    useEffect(() => {
        if (!map.current) return; // wait for map to initialize
        map.current.on('click', 'clusters', (e) => {
            const features = map.current.queryRenderedFeatures(e.point, {
                layers: ['clusters']
            });
            const clusterId = features[0].properties.cluster_id;
            map.current.getSource('earthquakes').getClusterExpansionZoom(
                clusterId,
                (err, zoom) => {
                    if (err) return;

                    map.current.easeTo({
                        center: features[0].geometry.coordinates,
                        zoom: zoom
                    });
                }
            );
        });
    });

    useEffect(() => {
        if (!map.current) return; // wait for map to initialize

        map.current.on('click', 'unclustered-point', (e) => {

            // const coordinates = e.features[0]?.geometry?.coordinates?.slice();
            // if (!coordinates) return;

            // const mag = e.features[0].properties.mag;
            // const tsunami =
            //     e.features[0].properties.tsunami === 1 ? 'yes' : 'no';
            //
            // while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
            //     coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
            // }

            // new mapboxgl.Popup()
            //     .setLngLat(coordinates)
            //     .setHTML(
            //         `magnitude: ${mag}<br>Was there a tsunami?: ${tsunami}`
            //     )
            //     .addTo(map);
        });
    });

    useEffect(() => {
        if (!map.current) return; // wait for map to initialize

        map.current.on('mouseenter', 'clusters', () => {
            map.current.getCanvas().style.cursor = 'pointer';
        });
    });

    useEffect(() => {
        if (!map.current) return; // wait for map to initialize

        map.current.on('mouseleave', 'clusters', () => {
            map.current.getCanvas().style.cursor = '';
        });
    });

    return (
        <Page fullWidth>
            <TitleBar title="App name" primaryAction={null}/>
            <Layout>
                <Layout.Section>
                    <Card sectioned>
                        <div className="map-wrapper">
                            <div className="sidebar">
                                Longitude: {lng} | Latitude: {lat} | Zoom: {zoom}
                            </div>
                            <div ref={mapContainer} className="map-container"/>
                        </div>
                    </Card>
                </Layout.Section>
                <Layout.Section secondary>
                    <Card sectioned title="Stores">
                        <p>Add tags to your order.</p>
                    </Card>
                </Layout.Section>
            </Layout>
        </Page>
    );
}
