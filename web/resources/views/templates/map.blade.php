<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Map HTML</title>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.13.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.13.0/mapbox-gl.js"></script>
</head>
<body>
<style>
    #map {
        height: var(--height-section, 400px);
    }

    .store-locator__container {
        display: flex;
        flex-direction: column;
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
        display: flex;
        overflow: auto;
        height: calc(100% - 42px);
        padding-right: 5px;
    }

    .store-locator__list::-webkit-scrollbar-track {
        background-color: #00000060;
    }

    .store-locator__list::-webkit-scrollbar-thumb {
        background-color: black;
    }

    .store-locator__item {
        padding: 15px;
        border: 0.5px solid #CDCDCD;
        cursor: pointer;
    }

    .store-locator__item.active {
        background-color: #F5F5F5;
    }

    .store-locator__info {
        display: flex;
    }

    .store-locator__info .store-locator__header {
        padding-bottom: 0;
        border-bottom: none;
    }

    .store-locator__img {
        width: 100%;
        height: 100%;
    }

    .store-locator__name {
        margin: 0 0 5px 0;
        font-weight: 500;
        font-size: 14px;
        line-height: 150%;
        color: #343434;
    }

    .store-locator__text {
        display: block;
        font-weight: 300;
        font-size: 14px;
        line-height: 150%;
        color: #343434;
    }

    .store-locator__header {
        font-weight: 700;
        padding-bottom: 10px;
        margin: 0 5px 0 0;
        text-transform: uppercase;
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
            gap: 20px;
        }

        .store-locator__list {
            gap: 20px;
            max-height: 260px;
        }

        .store-locator__list-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            column-gap: 20px;
            row-gap: 10px;
        }

        .store-locator__info {
            flex-direction: column;
            max-height: 250px;
            overflow: auto;
        }

        .store-locator__text-wrapper {
            padding: 10px;
        }

        .mapboxgl-popup-content {
            max-width: 300px;
        }
    }

    @media (min-width: 748px) {
        .store-locator__container {
            gap: 30px;
        }

        .store-locator__list {
            gap: 30px;
            max-height: 400px;
        }

        .store-locator__item {
            width: 100%;
        }

        .store-locator__info {
            flex-direction: row;
        }

        .store-locator__text-wrapper {
            padding: 20px;
        }

        .store-locator__image-wrapper,
        .store-locator__text-wrapper{
            width: 50%;
        }
    }

    .mapboxgl-popup-content {
        padding: 0;
    }
</style>

<div class="store-locator">
    <div class="store-locator__container">
        <div class="store-locator__column">
            <h2 class="store-locator__header">Our stores</h2>
            {{-- Что сделать список в строчку на моб устройстве нужно убрать класс - store-locator__list-grid --}}
            <div id="listStories" class="store-locator__list store-locator__list-grid">
                <div class="store-locator__item" data-slug="store-1">
                    <h3 class="store-locator__name">Sinsay in Plaza</h3>
                    <span class="store-locator__text">Rzeszow al. Tadeusza Rejtana 65 </span>
                </div>
                <div class="store-locator__item" data-slug="store-4">
                    <h3 class="store-locator__name">Sinsay in Galeria Rzeszow</h3>
                    <span class="store-locator__text">Rzeszow al. Józefa Piłsudskiego 44 </span>
                </div>
                <div class="store-locator__item" data-slug="store-5">
                    <h3 class="store-locator__name">Sinsay in Galeria Debicka</h3>
                    <span class="store-locator__text">Debica al. Józefa Piłsudskiego 44 </span>
                </div>
                <div class="store-locator__item" data-slug="store-6">
                    <h3 class="store-locator__name">Sinsay in Vivo! Krosno</h3>
                    <span class="store-locator__text">Krosno Bieszczadzka 29 </span>
                </div>
                <div class="store-locator__item" data-slug="teststore">
                    <h3 class="store-locator__name">TestStore3</h3>
                    <span class="store-locator__text">  </span>
                </div>
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
        "features": [{
            "type": "Feature",
            "geometry": {"type": "Point", "coordinates": ["22.058065321504", "51.076765340754"]},
            "properties": {
                "name": "Sinsay in Plaza",
                "slug": "store-1",
                "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquipex ea commodo consequat.",
                "address_1": "al. Tadeusza Rejtana 65",
                "address_2": null,
                "city": "Rzeszow",
                "postcode": "35-959",
                "state": null,
                "country": "Poland",
                "phone": "572864333",
                "fax": null,
                "site": "https:\/\/www.sinsay.com\/",
                "social_instagram": "https://instagram.com",
                "social_twitter": "https://twitter.com",
                "social_facebook": "https://facebook.com",
                "social_tiktok": "https://tiktok.com",
                "image_link": "https://ichef.bbci.co.uk/news/976/cpsprodpb/0ED2/production/_118149730_mediaitem118148499.jpg",
                "show_more_link": "https://google.com"
            }
        }, {
            "type": "Feature",
            "geometry": {"type": "Point", "coordinates": ["16.986600506914", "50.591018853195"]},
            "properties": {
                "name": "Sinsay in Galeria Rzeszow",
                "slug": "store-4",
                "description": null,
                "address_1": "al. J\u00f3zefa Pi\u0142sudskiego 44",
                "address_2": null,
                "city": "Rzeszow",
                "postcode": "35-001",
                "state": null,
                "country": "Poland",
                "phone": "177771867",
                "fax": null,
                "site": "https:\/\/www.sinsay.com\/",
                "social_instagram": null,
                "social_twitter": null,
                "social_facebook": null,
                "social_tiktok": null,
                "image_link": null
            }
        }, {
            "type": "Feature",
            "geometry": {"type": "Point", "coordinates": ["22.114808848959", "49.780853301518"]},
            "properties": {
                "name": "Sinsay in Galeria Debicka",
                "slug": "store-5",
                "description": null,
                "address_1": "al. J\u00f3zefa Pi\u0142sudskiego 44",
                "address_2": null,
                "city": "Debica",
                "postcode": "39-200",
                "state": null,
                "country": "Poland",
                "phone": "177771867",
                "fax": null,
                "site": "https:\/\/www.sinsay.com\/",
                "social_instagram": null,
                "social_twitter": null,
                "social_facebook": null,
                "social_tiktok": null,
                "image_link": null
            }
        }, {
            "type": "Feature",
            "geometry": {"type": "Point", "coordinates": ["20.237621801766", "51.404525312182"]},
            "properties": {
                "name": "Sinsay in Vivo! Krosno",
                "slug": "store-6",
                "description": null,
                "address_1": "Bieszczadzka 29",
                "address_2": null,
                "city": "Krosno",
                "postcode": "38-400",
                "state": null,
                "country": "Poland",
                "phone": "519016358",
                "fax": null,
                "site": "https:\/\/www.sinsay.com\/",
                "social_instagram": null,
                "social_twitter": null,
                "social_facebook": null,
                "social_tiktok": null,
                "image_link": null
            }
        }, {
            "type": "Feature",
            "geometry": {"type": "Point", "coordinates": ["8.3321113903999", "42.756989423067"]},
            "properties": {
                "name": "TestStore3",
                "slug": "teststore",
                "description": null,
                "address_1": null,
                "address_2": null,
                "city": null,
                "postcode": null,
                "state": null,
                "country": null,
                "phone": null,
                "fax": null,
                "site": null,
                "social_instagram": null,
                "social_twitter": null,
                "social_facebook": null,
                "social_tiktok": null,
                "image_link": null
            }
        }]
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
        if (activeElement) {
            document.querySelector(`[data-slug="${activeElement?.properties?.slug}"]`).classList.remove('active');
        }
        activeElement = element;

        if (activeElement) {
            document.querySelector(`[data-slug="${activeElement.properties.slug}"]`).classList.add('active');

            coordinates = coordinates?.slice() || element.geometry.coordinates.slice();
            const properties = element.properties;

            map.easeTo({
                center: [coordinates[0], parseFloat(coordinates[1]) + 0.005],
                zoom: 14
            });

            popUP
                .setLngLat(coordinates)
                .setHTML(renderPopup(properties))
                .setMaxWidth("682px")
                .addTo(map);
        }
    }

    const renderPopup = (info = {}) => {
        let result = `<div class="store-locator__info">
${(info.image_link) ? `<div class="store-locator__image-wrapper"><img src="${info.image_link}" class="store-locator__img"></div>` : ''}
                    <div class="store-locator__text-wrapper">
${(info.name) ? `<h3 class="store-locator__name">${info.name}</h3>` : ''}
${(info.address_1) ? `<span class="store-locator__text">${info.address_1}</span>` : ''}
${(info.address_2) ? `<span class="store-locator__text">${info.address_2}</span>` : ''}
${(info.description) ? `<span class="store-locator__text">${info.description}</span>` : ''}
${(info.city || info.country) ? `<span class="store-locator__group">
        ${(info.city) ? `<span class="store-locator__text">${info.city}</span>` : ''}
        ${(info.country) ? `<span class="store-locator__text">${info.country}</span>` : ''}
    </span>` : ''}
    <h5 class="store-locator__header">Contacts</h5>
    ${(info.phone) ? `<a href="tel:${info.phone}" class="store-locator__text">${info.phone}</a>` : ''}
    ${(info.fax) ? `<span class="store-locator__text">${info.fax}</span>` : ''}
    ${(info.site) ? `<a href="${info.site}" class="store-locator__text">${info.site}</a>` : ''}
    <div class="store-locator__social-container">
        ${(info.social_instagram) ? `<a href="#" class="store-locator__social"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
             style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2"
             viewBox="0 0 512 512"><path
                d="M449.446 0C483.971 0 512 28.03 512 62.554v386.892C512 483.97 483.97 512 449.446 512H62.554C28.03 512 0 483.97 0 449.446V62.554C0 28.03 28.029 0 62.554 0h386.892ZM256 81c-47.527 0-53.487.201-72.152 1.053-18.627.85-31.348 3.808-42.48 8.135-11.508 4.472-21.267 10.456-30.996 20.184-9.729 9.729-15.713 19.489-20.185 30.996-4.326 11.132-7.284 23.853-8.135 42.48C81.201 202.513 81 208.473 81 256s.201 53.487 1.052 72.152c.851 18.627 3.809 31.348 8.135 42.48 4.472 11.507 10.456 21.267 20.185 30.996s19.488 15.713 30.996 20.185c11.132 4.326 23.853 7.284 42.48 8.134C202.513 430.799 208.473 431 256 431s53.487-.201 72.152-1.053c18.627-.85 31.348-3.808 42.48-8.134 11.507-4.472 21.267-10.456 30.996-20.185s15.713-19.489 20.185-30.996c4.326-11.132 7.284-23.853 8.134-42.48C430.799 309.487 431 303.527 431 256s-.201-53.487-1.053-72.152c-.85-18.627-3.808-31.348-8.134-42.48-4.472-11.507-10.456-21.267-20.185-30.996-9.729-9.728-19.489-15.712-30.996-20.184-11.132-4.327-23.853-7.285-42.48-8.135C309.487 81.201 303.527 81 256 81Zm0 31.532c46.727 0 52.262.178 70.715 1.02 17.062.779 26.328 3.63 32.495 6.025 8.169 3.175 13.998 6.968 20.122 13.091 6.124 6.124 9.916 11.954 13.091 20.122 2.396 6.167 5.247 15.433 6.025 32.495.842 18.453 1.021 23.988 1.021 70.715 0 46.727-.179 52.262-1.021 70.715-.778 17.062-3.629 26.328-6.025 32.495-3.175 8.169-6.967 13.998-13.091 20.122-6.124 6.124-11.953 9.916-20.122 13.091-6.167 2.396-15.433 5.247-32.495 6.025-18.45.842-23.985 1.021-70.715 1.021-46.73 0-52.264-.179-70.715-1.021-17.062-.778-26.328-3.629-32.495-6.025-8.169-3.175-13.998-6.967-20.122-13.091-6.124-6.124-9.917-11.953-13.091-20.122-2.396-6.167-5.247-15.433-6.026-32.495-.842-18.453-1.02-23.988-1.02-70.715 0-46.727.178-52.262 1.02-70.715.779-17.062 3.63-26.328 6.026-32.495 3.174-8.168 6.967-13.998 13.091-20.122 6.124-6.123 11.953-9.916 20.122-13.091 6.167-2.395 15.433-5.246 32.495-6.025 18.453-.842 23.988-1.02 70.715-1.02Zm0 53.603c-49.631 0-89.865 40.234-89.865 89.865 0 49.631 40.234 89.865 89.865 89.865 49.631 0 89.865-40.234 89.865-89.865 0-49.631-40.234-89.865-89.865-89.865Zm0 148.198c-32.217 0-58.333-26.116-58.333-58.333s26.116-58.333 58.333-58.333 58.333 26.116 58.333 58.333-26.116 58.333-58.333 58.333Zm114.416-151.748c0 11.598-9.403 20.999-21.001 20.999-11.597 0-20.999-9.401-20.999-20.999 0-11.598 9.402-21 20.999-21 11.598 0 21.001 9.402 21.001 21Z"/>
        </svg></a>` : ''}
        ${(info.social_twitter) ? `<a href="#" class="store-locator__social"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
             style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2"
             viewBox="0 0 512 512"><path
                d="M449.446 0C483.971 0 512 28.03 512 62.554v386.892C512 483.97 483.97 512 449.446 512H62.554C28.03 512 0 483.97 0 449.446V62.554C0 28.03 28.029 0 62.554 0h386.892ZM195.519 424.544c135.939 0 210.268-112.643 210.268-210.268 0-3.218 0-6.437-.153-9.502 14.406-10.421 26.973-23.448 36.935-38.314-13.18 5.824-27.433 9.809-42.452 11.648 15.326-9.196 26.973-23.602 32.49-40.92-14.252 8.429-30.038 14.56-46.896 17.931-13.487-14.406-32.644-23.295-53.946-23.295-40.767 0-73.87 33.104-73.87 73.87 0 5.824.613 11.494 1.992 16.858-61.456-3.065-115.862-32.49-152.337-77.241-6.284 10.881-9.962 23.601-9.962 37.088a73.57 73.57 0 0 0 32.95 61.456c-12.107-.307-23.448-3.678-33.41-9.196v.92c0 35.862 25.441 65.594 59.311 72.49a73.66 73.66 0 0 1-19.464 2.606c-4.751 0-9.348-.46-13.946-1.38 9.349 29.426 36.628 50.728 68.965 51.341-25.287 19.771-57.164 31.571-91.8 31.571-5.977 0-11.801-.306-17.625-1.073 32.337 21.15 71.264 33.41 112.95 33.41Z"/>
        </svg></a>` : ''}
        ${(info.social_facebook) ? `<a href="#" class="store-locator__social"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
             style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2"
             viewBox="0 0 512 512"><path
                d="M449.446 0C483.971 0 512 28.03 512 62.554v386.892C512 483.97 483.97 512 449.446 512H342.978V319.085h66.6l12.672-82.621h-79.272v-53.617c0-22.603 11.073-44.636 46.58-44.636H425.6v-70.34s-32.71-5.582-63.982-5.582c-65.288 0-107.96 39.569-107.96 111.204v62.971h-72.573v82.621h72.573V512H62.554C28.03 512 0 483.97 0 449.446V62.554C0 28.03 28.029 0 62.554 0h386.892Z"/>
        </svg></a>` : ''}
        ${(info.social_tiktok) ? `<a href="#" class="store-locator__social"><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
             style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2"
             viewBox="0 0 512 512"><path
                d="M449.446 0C483.971 0 512 28.03 512 62.554v386.892C512 483.97 483.97 512 449.446 512H62.554C28.03 512 0 483.97 0 449.446V62.554C0 28.03 28.029 0 62.554 0h386.892Zm-58.673 127.703c-33.842-33.881-78.847-52.548-126.798-52.568-98.799 0-179.21 80.405-179.249 179.234-.013 31.593 8.241 62.428 23.927 89.612l-25.429 92.884 95.021-24.925c26.181 14.28 55.659 21.807 85.658 21.816h.074c98.789 0 179.206-80.413 179.247-179.243.018-47.895-18.61-92.93-52.451-126.81ZM263.976 403.485h-.06c-26.734-.01-52.954-7.193-75.828-20.767l-5.441-3.229-56.386 14.792 15.05-54.977-3.542-5.637c-14.913-23.72-22.791-51.136-22.779-79.287.033-82.142 66.867-148.971 149.046-148.971 39.793.014 77.199 15.531 105.329 43.692 28.128 28.16 43.609 65.592 43.594 105.4-.034 82.149-66.866 148.983-148.983 148.984Zm81.721-111.581c-4.479-2.242-26.499-13.075-30.604-14.571-4.105-1.495-7.091-2.241-10.077 2.241-2.986 4.483-11.569 14.572-14.182 17.562-2.612 2.988-5.225 3.364-9.703 1.12-4.479-2.241-18.91-6.97-36.017-22.23C231.8 264.15 222.81 249.484 220.198 245c-2.612-4.484-.279-6.908 1.963-9.14 2.016-2.007 4.48-5.232 6.719-7.847 2.24-2.615 2.986-4.484 4.479-7.472 1.493-2.99.747-5.604-.374-7.846-1.119-2.241-10.077-24.288-13.809-33.256-3.635-8.733-7.327-7.55-10.077-7.688-2.609-.13-5.598-.158-8.583-.158-2.986 0-7.839 1.121-11.944 5.604-4.105 4.484-15.675 15.32-15.675 37.364 0 22.046 16.048 43.342 18.287 46.332 2.24 2.99 31.582 48.227 76.511 67.627 10.685 4.615 19.028 7.371 25.533 9.434 10.728 3.41 20.492 2.929 28.209 1.775 8.605-1.285 26.499-10.833 30.231-21.295 3.732-10.464 3.732-19.431 2.612-21.298-1.119-1.869-4.105-2.99-8.583-5.232Z"/>
        </svg></a>` : ''}
    </div>
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

        document.querySelector('#listStories').addEventListener('click', (event) => {
            let element = event.target.closest('[data-slug]');

            let activeElement = geojson.features.find(feature => feature.properties.slug === element.dataset.slug);

            activeteElement(activeElement);
        });

        addMarkers(geojson.features);
    });

    const addMarkers = (features) => {
        for (const feature of features) {
            const el = document.createElement('div');
            el.className = 'marker';
            el.style.backgroundImage = `url(https://placekitten.com/g/40/40/)`;
            el.style.width = `40px`;  // Здесь можно указывать размер (ширина) маркера
            el.style.height = `40px`; // Здесь можно указывать размер (высота) маркера

            new mapboxgl.Marker(el).setLngLat(feature.geometry.coordinates).addTo(map);
        }
    }
</script>
</body>
</html>
