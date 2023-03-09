<link href="https://api.mapbox.com/mapbox-gl-js/v2.13.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.13.0/mapbox-gl.js"></script>

<style>
    #map {
        height: var(--height-section, 400px);
    }

    .store-locator__container {
        display: flex;
        max-width: 1440px;
        margin: var(--margin-top, 20px) auto var(--margin-bottom, 20px);
        padding: 0 20px;
        gap: 5px;
    }

    .store-locator__column {
        flex: 2;
        height: var(--height-section, 400px);
    }

    .store-locator__map {
        width: 100%;
        flex: 4;
        height: var(--height-section, 400px);
    }

    .store-locator__list {
        overflow: auto;
        height: calc(100% - 42px);
        padding-right: 5px;
        max-height: 400px;
    }

    .store-locator__list::-webkit-scrollbar {
        width: 2px;
    }

    .store-locator__list::-webkit-scrollbar-track {
        background-color: #00000060;
    }

    .store-locator__list::-webkit-scrollbar-thumb {
        background-color: black;
    }

    .store-locator__item {
        padding: 5px;
        border-bottom: 1px solid black;
        cursor: pointer;
    }

    .store-locator__item.active {
        background-color: #f1f1f1;
    }

    .store-locator__info .store-locator__header {
        padding-bottom: 0;
        border-bottom: none;
    }

    .store-locator__name {
        margin: 0;
        font-weight: 700;
    }

    .store-locator__text {
        display: block;
    }

    .store-locator__header {
        font-weight: 700;
        padding-bottom: 10px;
        border-bottom: 1px solid black;
        margin: 0 5px 0 0;
    }

    .store-locator__social-container {
        display: flex;
        gap: 5px;
    }

    .store-locator__social {
        display: flex;
        align-items: center;
        text-decoration: none;
        gap: 5px;
        color: currentColor;
    }

    .store-locator__social svg {
        width: 20px;
        height: 20px;
        fill: currentColor;
    }

    @media (max-width: 748px) {
        .store-locator__container {
            flex-direction: column-reverse;
        }
    }
</style>

<div class="store-locator">
    <div class="store-locator__container">
        <div class="store-locator__column">
            <h2 class="store-locator__header">Our stores</h2>
            <div id="listStories" class="store-locator__list">
                @foreach ($geo as $getItem)
                    <div class="store-locator__item" data-slug="{{ $getItem['properties']['slug'] }}">
                        <h3 class="store-locator__name">{{ $getItem['properties']['name'] }}</h3>
                        <span class="store-locator__text">{{ $getItem['properties']['city'] }} {{ $getItem['properties']['address_1'] }} {{ $getItem['properties']['address_2'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="store-locator__map">
            <div id="map"></div>
        </div>
    </div>
</div>

<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiZGFudmVyeXVyayIsImEiOiJjbGE4MTIzam0wZ2Z3M3ZtcTFlYWc0ZnJ0In0.xOVF1gHOacyuHOe2-LSXZw';

    const geojson = {
        "type": "FeatureCollection",
        "features": @json($geo)
    };

    let viewStore = [];

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: [22.0404986, 50.033416],
        zoom: 8
    });

    let popUP = new mapboxgl.Popup();

    const listStore = document.querySelector('#listStories');

    let activeElement = null;

    const activeteElement = (element, coordinates = null) => {
        if (activeElement) { document.querySelector(`[data-slug="${activeElement?.properties?.slug}"]`).classList.remove('active'); }
        activeElement = element;

        if (activeElement) {
            document.querySelector(`[data-slug="${activeElement.properties.slug}"]`).classList.add('active');

            coordinates = coordinates?.slice() || element.geometry.coordinates.slice();
            const properties = element.properties;

            map.easeTo({
                center: [coordinates[0], parseFloat(coordinates[1])+0.005],
                zoom: 14
            });

            popUP
                .setLngLat(coordinates)
                .setHTML(renderPopup(properties))
                .addTo(map);
        }
    }

    const renderPopup = (info = {}) => {
        let result = `<div class="store-locator__info">
${(info.name) ? `<h3 class="store-locator__name">${info.name}</h3>` : ''}
${(info.address_1) ? `<span class="store-locator__text">${info.address_1}</span>` : ''}
${(info.address_2) ? `<span class="store-locator__text">${info.address_2}</span>` : ''}
${(info.city || info.country) ? `<span class="store-locator__group">
        ${(info.city) ? `<span class="store-locator__text">${info.city}</span>` : ''}
        ${(info.country) ? `<span class="store-locator__text">${info.country}</span>` : ''}
    </span>` : ''}
    <h5 class="store-locator__header">Contacts</h5>
    ${(info.phone) ? `<span class="store-locator__text">${info.phone}</span>` : ''}
    ${(info.fax) ? `<span class="store-locator__text">${info.fax}</span>` : ''}
    ${(info.site) ? `<a href="${info.site}" class="store-locator__text">${info.site}</a>` : ''}
    <div class="store-locator__social-container">
        ${(info.social_instagram) ? `<a href="#" class="store-locator__social">{!! $social['instagram']['icon'] !!}</a>` : ''}
        ${(info.social_twitter) ? `<a href="#" class="store-locator__social">{!! $social['twitter']['icon'] !!}</a>` : ''}
        ${(info.social_facebook) ? `<a href="#" class="store-locator__social">{!! $social['facebook']['icon'] !!}</a>` : ''}
        ${(info.social_tiktok) ? `<a href="#" class="store-locator__social">{!! $social['tiktok']['icon'] !!}</a>` : ''}
    </div>
</div>`;

        return result;
    }

    const getUniqueFeatures = (features, comparatorProperty) => {
        const uniqueIds = new Set();
        const uniqueFeatures = [];
        for (const feature of features) {
            const id = feature.properties[comparatorProperty];
            if (!uniqueIds.has(id)) {
                uniqueIds.add(id);
                uniqueFeatures.push(feature);
            }
        }
        return uniqueFeatures;
    }

    const renderListStories = (features) => {
        let items = '';

        features.forEach(feature => {
            let activeClass = (feature.properties.slug === activeElement?.properties?.slug) ? 'active' : '';

            items += `<div class="store-locator__item ${activeClass}" data-slug="${feature.properties.slug}">
                    <h3 class="store-locator__name">${feature.properties.name}</h3>
                    <span class="store-locator__text">${feature.properties.city}, ${feature.properties.address_1}, ${feature.properties.address_2}</span>
                </div>`
        });

        listStore.innerHTML = items;
    }

    map.on('load', () => {
        map.addSource('earthquakes', {
            type: 'geojson',
            data: geojson,
            cluster: false,
            clusterMaxZoom: 14, // Max zoom to cluster points on
            clusterRadius: 50 // Radius of each cluster when clustering points (defaults to 50)
        });

        map.addControl(new mapboxgl.NavigationControl({
            showCompass: false
        }), 'top-left');

        map.addLayer({
            id: 'store',
            type: 'circle',
            source: 'earthquakes',
            paint: {
                'circle-color': '#4264fb',
                'circle-radius': 4,
                'circle-stroke-width': 2,
                'circle-stroke-color': '#ffffff'
            }
        });

        const updateListStories = (features = []) => {
            features = features.length ? features : map.queryRenderedFeatures({ layers: ['store'] });

            if (features) {
                const uniqueFeatures = getUniqueFeatures(features, 'slug');

                renderListStories(uniqueFeatures);

                viewStore = uniqueFeatures;
            }
        }

        // const features = geojson.features.filter(feature => map.getBounds().contains(feature.geometry.coordinates));
        // updateListStories(features);

        map.on('click', () => {
            activeteElement(null);
        });

        map.on('click', 'store', (e) => {
            const coordinates = e.features[0].geometry.coordinates.slice();

            while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
            }

            activeteElement(e.features[0], coordinates);
        });

        // map.on('moveend', (e) => {
        //     updateListStories();
        // });

        document.querySelector('#listStories').addEventListener('click', (event) => {
            let element = event.target.closest('[data-slug]');

            let activeElement = geojson.features.find(feature => feature.properties.slug === element.dataset.slug);

            activeteElement(activeElement);
        });

        // popUP.on('close', () => {
        //     if (activeElement) activeteElement(null);
        // });
    });
</script>
