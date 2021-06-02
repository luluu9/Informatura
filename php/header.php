<script>
function searchShow() {
    let search_type = document.getElementById("search-type");
    let search_btn = document.getElementById("search-btn");
    if (search_btn.innerHTML == '🔍') {
        search_type.style.height = "5rem";
        search_type.style.padding = ".5rem 1rem";
        search_btn.innerHTML = '❌';
    }
    else {
        search_type.style.height = "0";
        search_type.style.padding = "0";
        search_btn.innerHTML = '🔍';
    }
}
</script>
<header>
    <div id=logo>
        <div id=title>
            <a href="/glowna">
                I<span style="letter-spacing: 0.4rem;">N<span class="red">FOR</span></span>MATURA
            </a>
        </div>
        <div id=subtitle>dla każdego</div>
    </div>
    <input class="menu-btn" type="checkbox" id="menu-btn" />
    <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
    <div class="break"></div>
    <div id="nav-container">
        <div id="navbar">
            <ul id="main-menu">
                <li><a href="/glowna">Strona główna</a></li>
                <li><a href="/kontakt">Kontakt</a></li>
                <li><a href="/arkusze">Arkusze</a></li>
                <li><a href="/rozwiazania">Rozwiązania</a></li>
            </ul>
        </div>
        <div class="break"></div>
        <div id="search-box" class="center">
            <button id="search-btn" onclick="searchShow()">🔍</button>
        </div>
    </div>
    <form id="search-bar" method="POST" action="/wyszukaj">
        <input id="search-type" type="text" placeholder="Szukam..." aria-label="Szukam..." name="search-type">
    </form> 
</header>