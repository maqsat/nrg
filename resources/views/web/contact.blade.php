@extends('layouts.landing')
@section("custom_style")
    <link rel="stylesheet" href="/css/tab.css">
    <style>
        #map {
            width: 100%; height: 400px; padding: 0; margin: 0;
        }
    </style>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=<ваш API-ключ>" type="text/javascript"></script>
    <script>
        ymaps.ready(init);
        function init() {
            window.myMap = new ymaps.Map('map', {
                center: [51.12026407265477,71.45882499999999],
                zoom: 10
            }, {
                searchControlProvider: 'yandex#search'
            });
            myMap.geoObjects
                .add(new ymaps.Placemark([51.12026407265477,71.45882499999999], {
                    balloonContent: '<strong>Enrise</strong>'
                }, {
                    preset: 'islands#dotIcon',
                    iconColor: '#735184'
                }));
        }
    </script>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="container  fs-18">
                <div class="row section-row">
                    <div class="tabset">
                        <!-- Tab 1 -->
                        <input type="radio" name="tabset" id="tab1" onclick="changeMarker([51.12026407265477,71.45882499999999])" aria-controls="marzen" checked>
                        <label for="tab1">Нұрсұлтан</label>
                        <!-- Tab 2 -->
                        <input type="radio" name="tabset" onclick="changeMarker([42.32565407435969,69.60278850000002])" id="tab2" aria-controls="rauchbier">
                        <label for="tab2">Шымкент</label>
                        <!-- Tab 3 -->
                        <input type="radio" name="tabset" onclick="changeMarker([50.28462007307812,57.225908500000045])" id="tab3" aria-controls="dunkles">
                        <label for="tab3">Ақтөбе</label>
                        <!-- Tab 4 -->
                        <input type="radio" name="tabset" onclick="changeMarker([51.224054072584025,51.40153749999995])" id="tab4" aria-controls="dunkles">
                        <label for="tab4">Орал</label>
                        <!-- Tab 5 -->
                        <input type="radio" name="tabset" onclick="changeMarker([43.687155074562334,51.17743499999998])" id="tab5" aria-controls="dunkles">
                        <label for="tab5">Ақтау</label>

                        <div class="tab-panels">
                            <section id="marzen" class="tab-panel">
                                <div class="col-md-6">
                                    <h2>Контакты</h2>
                                    <p><strong>Адрес:</strong> ул Нажимеденова 4</p>
                                    <p><strong>Телефон:</strong> +7(775) 186 96 26</p>
                                    <p><strong>Ответственное лицо:</strong> Асылбек Алишахманов</p>
                                </div>
                            </section>
                            <section id="rauchbier" class="tab-panel">
                                <div class="col-md-6">
                                    <h2>Контакты</h2>
                                    <p><strong>Адрес:</strong> ул Торекулова 12</p>
                                    <p><strong>Телефон:</strong> +7(771) 307 43 43</p>
                                    <p><strong>Ответственное лицо:</strong> Исаев Ануар</p>
                                </div>
                            </section>
                            <section id="dunkles" class="tab-panel">
                                <div class="col-md-6">
                                    <h2>Контакты</h2>
                                    <p><strong>Адрес:</strong> ул Алтынсарина 10А</p>
                                    <p><strong>Телефон:</strong> +7(701) 345 25 21</p>
                                    <p><strong>Ответственное лицо:</strong> Шагиров Нуржан</p>
                                </div>
                            </section>
                            <section id="dunkles" class="tab-panel">
                                <div class="col-md-6">
                                    <h2>Контакты</h2>
                                    <p><strong>Адрес:</strong> ул Евразия 169/2</p>
                                    <p><strong>Телефон:</strong> +7(702) 121 44 65</p>
                                    <p><strong>Ответственное лицо:</strong> Бисманова Нурслу </p>
                                </div>
                            </section>
                            <section id="dunkles" class="tab-panel">
                                <div class="col-md-6">
                                    <h2>Контакты</h2>
                                    <p><strong>Адрес:</strong> микрорайон 32Б, 7 здание</p>
                                    <p><strong>Телефон:</strong> +7(708) 920 92 89</p>
                                    <p><strong>Ответственное лицо:</strong> Торегулов Габдол</p>
                                </div>
                            </section>
                        </div>

                    </div>
                </div>
                <div id="map"></div>
            </div>
        </div>
    </div>
    <script>
        function changeMarker(cords){
            myMap.geoObjects.removeAll();
            myMap.geoObjects.add(new ymaps.Placemark(cords, {
                balloonContent: '<strong>Enrise</strong>'
            }, {
                preset: 'islands#dotIcon',
                iconColor: '#735184'
            }));
            myMap.setCenter(cords, 10);
        }
    </script>
@endsection