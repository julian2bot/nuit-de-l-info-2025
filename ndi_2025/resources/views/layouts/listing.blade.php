<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('style/infinityScroll.css') }}">
@endsection


<div class="content">

    <div class="entete-info">


        @yield('titreContent')

        <form class="search-input" action="#">
            <div>
                <input type="text" id="search" placeholder="Rechercher..." />
                <input type="hidden" id="directionInput" name="direction" value="asc">
            </div>

            <div>
                <select id="sort">
                    <option value="">Aucun tri</option>

                    @yield('sort')
                </select>
            </div>

            <div title="Changer l'ordre du tri" class="orderBy" id="direction"  onclick="orderBySwitch()">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-80 200-360l56-57 184 184v-287h80v287l184-183 56 56L480-80Zm-40-520v-120h80v120h-80Zm0-200v-80h80v80h-80Z"/></svg>
            </div>

            <button class="btn-searcph" type="button" onclick="resetAndLoad()">Chercher</button>
        </form>
    </div>

    <br>

    <div id="container"></div>

    <div id="sentinel" style="height:20px;">
        <div id="loader" style="display:none;">
            <svg width="50" height="50" viewBox="0 0 50 50">
                <circle cx="25" cy="25" r="20" stroke="#1f1f1f"
                    stroke-width="5" fill="none" stroke-linecap="round">
                    <animateTransform attributeName="transform" type="rotate"
                        from="0 25 25" to="360 25 25" dur="1s" repeatCount="indefinite" />
                    <animate attributeName="stroke-dasharray"
                        values="1,150;90,150;1,150" dur="1.5s" repeatCount="indefinite" />
                </circle>
            </svg>
        </div>
    </div>

</div>

@yield('scriptConst')

@yield('script')
<script src="{{ asset('js/scroll/infinityScroll.js') }}"></script>


@yield('styleCard')

