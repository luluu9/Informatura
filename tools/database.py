import os
from datetime import datetime
from MySQLdb import _mysql, converters
from ftplib import FTP_TLS


def query(query):
    db.query(query)


def fetch_row(query):
    db.query(query)
    result = db.store_result()
    wynik = result.fetch_row()
    return wynik


def as_string(string):
    return "'"+string+"'"


def arkusz_istnieje(nazwa):
    wynik = fetch_row(
        "SELECT id FROM arkusze WHERE CONCAT(rok, ' ', opis) = " + nazwa)
    if not wynik:
        return False
    return wynik[0][0]


def stworz_baze(db_name="informat_website"):
    query = f"CREATE DATABASE IF NOT EXISTS `{db_name}` CHARACTER SET UTF8mb4 COLLATE utf8mb4_bin;"
    db.query(query)
    db.select_db(db_name)
    

def stworz_tabele_arkusze():
    query = "CREATE TABLE IF NOT EXISTS `arkusze` ( " \
        "`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , " \
        "`rok` VARCHAR(15) NOT NULL, " \
        "`opis` VARCHAR(15) NOT NULL , " \
        "PRIMARY KEY (`id`)) " \
        "ENGINE = InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_polish_ci;"
    db.query(query)


def stworz_tabele_zadania():
    db.query("CREATE TABLE IF NOT EXISTS zadania ( "
             "`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , "
             "`id_arkusza` INT UNSIGNED NOT NULL , "
             "`dirpath` VARCHAR(200) NOT NULL , "
             "`filename` VARCHAR(50) NOT NULL , "
             "`autor` VARCHAR(100) , "
             "`tresc` TEXT , "
             "`punkty` INT UNSIGNED , "
             "`rozwiazanie` TEXT , "
             "`data_modyfikacji` DATETIME , "
             "`data_utworzenia` DATETIME , "
             "FULLTEXT (tresc,rozwiazanie) , " 
             "PRIMARY KEY (`id`) , "
             "FOREIGN KEY (`id_arkusza`) REFERENCES arkusze(`id`)) "
             "ENGINE = InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_polish_ci;")


def stworz_tabele_tagi():
    db.query("CREATE TABLE IF NOT EXISTS tagi ( "
             "`id_zadania` INT UNSIGNED NOT NULL , "
             "`tag` VARCHAR(50) NOT NULL , "
             "PRIMARY KEY (`id_zadania`, `tag`) , "
             "FOREIGN KEY (`id_zadania`) REFERENCES zadania(`id`)) "
             "ENGINE = InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_polish_ci;")


def stworz_tabele_uploads():
    db.query("""CREATE TABLE IF NOT EXISTS uploads (
            `sha256` VARCHAR(64) NOT NULL,
            `filepath` VARCHAR(100) NOT NULL,
            `filename` VARCHAR(100) NOT NULL,
            `author` VARCHAR(100),
            `sheet_info` VARCHAR(100),
            `other_info` TEXT,
            `upload_date` DATETIME,
            `mime` VARCHAR(100),
            `sha256_vt` VARCHAR(64),
            `positive` INT UNSIGNED,
            PRIMARY KEY (`sha256`))
            ENGINE = InnoDB DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_polish_ci;""")


def dodaj_obrazek(filepath, remote_dirpath, filename):
    if not ftps:
        return
    filename += ".png"
    filepath += ".png"
    print(filepath)
    if os.path.exists(filepath):
        with open(filepath, "rb") as img:
            remote_filepath = remote_dirpath + filename
            if not remote_filepath in ftps.nlst(remote_dirpath):
                print("Dodawanie obrazka:", remote_filepath)
                ftps.storbinary(f"STOR {remote_filepath}", img)

def dodaj_tagi(id_zadania, tagi):
    for tag in tagi:
        # IGNORE when exists
        db.query(
            f"INSERT IGNORE INTO tagi (id_zadania, tag) VALUES ({id_zadania}, {as_string(tag)});")


def kiedy_utworzono(filepath):
    create_time = os.path.getctime(filepath)
    return datetime.fromtimestamp(create_time).strftime('%Y-%m-%d %H:%M:%S')


def kiedy_zmodyfikowano(filepath):
    mod_time = os.path.getmtime(filepath)
    return datetime.fromtimestamp(mod_time).strftime('%Y-%m-%d %H:%M:%S')


def czytaj_zadanie(filepath):
    def przypisz_kontekst(line):
        if line.startswith("tagi"):
            return "tagi"
        if line.lower().startswith("punkty"):
            return "punkty"
        elif line.lower().startswith("tresc"):
            return "tresc"
        elif line.lower().startswith("autor"):
            return "autor"
        elif line.lower().startswith("rozwiazanie"):
            return "rozwiazanie"

    zadanie = {"dirpath": os.path.dirname(os.path.relpath(filepath, website_root)).replace('\\', '/')+"/",
               "filename": os.path.basename(filepath)[:-4],
               "autor": "",
               "tresc": "",
               "punkty": 0,
               "rozwiazanie": "",
               "tagi": [],
               "data_utworzenia": kiedy_utworzono(filepath),
               "data_modyfikacji": kiedy_zmodyfikowano(filepath)
               }

    kontekst = ""  # zależności od kontekstu zapisujemy do tresc/rozw/tagi
    with open(filepath, "r", encoding="utf-8") as zadanie_f:
        for line in zadanie_f:
            wynik = przypisz_kontekst(line.strip().lower())
            if wynik:
                kontekst = wynik
                continue
            if kontekst == "tagi":
                zadanie["tagi"].extend(
                    [x.strip() for x in line.split(",") if x.strip() != ""])
            elif kontekst == "tresc":
                zadanie["tresc"] += line
            elif kontekst == "punkty":
                if not line.strip():
                    continue
                zadanie["punkty"] = int(line.strip())
            elif kontekst == "autor":
                zadanie["autor"] = line.strip()
            elif kontekst == "rozwiazanie":
                zadanie["rozwiazanie"] += line
            else:
                print("brak kontekstu!", line)

    zadanie["tresc"] = zadanie["tresc"].strip()
    zadanie["rozwiazanie"] = zadanie["rozwiazanie"].strip()

    return zadanie


def dodaj_zadanie(id_arkusza, filepath):
    print("Dodawanie:", filepath)
    try:
        zadanie = czytaj_zadanie(filepath)
    except OSError:
        print("Nie można uzyskać dostępu do", filepath)
        return
    id_zadania = fetch_row("SELECT id FROM zadania WHERE CONCAT(dirpath,filename)=" +
                           as_string(zadanie['dirpath']+zadanie['filename']))
    if not id_zadania:
        query("INSERT INTO zadania(id_arkusza, dirpath, filename, autor, tresc, punkty, rozwiazanie, data_modyfikacji, data_utworzenia) VALUES ("
              f"{ id_arkusza }, "
              f"{ as_string(zadanie['dirpath']) }, "
              f"{ as_string(zadanie['filename']) }, "
              f"{ as_string(zadanie['autor']) }, "
              f"{ as_string(zadanie['tresc']) }, "
              f"{ zadanie['punkty']}, "
              f"{ as_string(zadanie['rozwiazanie']) }, "
              f"{ as_string(zadanie['data_modyfikacji']) }, "
              f"{ as_string(zadanie['data_utworzenia']) });")
        id_zadania = fetch_row("SELECT id FROM zadania WHERE CONCAT(dirpath,filename)=" +
                        as_string(zadanie['dirpath']+zadanie['filename']))[0][0]
    else:
        id_zadania = id_zadania[0][0]
        query("UPDATE zadania SET "
              f"id_arkusza = { id_arkusza }, "
              f"tresc = { as_string(zadanie['tresc']) }, "
              f"rozwiazanie = { as_string(zadanie['rozwiazanie']) }, "
              f"punkty = { zadanie['punkty'] }, "
              f"autor = { as_string(zadanie['autor']) }, "
              f"data_modyfikacji = { as_string(zadanie['data_modyfikacji']) } "
              f"WHERE id = { id_zadania };")
    dodaj_tagi(id_zadania, zadanie['tagi'])
    dodaj_obrazek(filepath[:-4], zadanie["dirpath"], zadanie["filename"])


def przesledz_zadania(rootpath, id_arkusza):
    for zadanie in os.listdir(rootpath):
        filepath = os.path.join(rootpath, zadanie)
        if os.path.isfile(filepath) and zadanie[-3:] == "txt" and not filepath.endswith(("linki.txt", "glowne.txt")):
            dodaj_zadanie(id_arkusza, filepath)


def sprawdz_linki(rootpath, id_arkusza):
    linki = {"url_teoria": "",
             "url_praktyka": ""}
    if os.path.exists(rootpath+"linki.txt"):
        with open(rootpath+"linki.txt", "r", encoding="utf-8") as linki_f:
            for line in linki_f:
                if line.lower().startswith("teoria:"):
                    linki["url_teoria"] = line[7:].strip()
                elif line.lower().startswith("praktyka:"):
                    linki["url_praktyka"] = line[9:].strip()
        for key, value in linki.items():
            if value:
                query(
                    f"UPDATE arkusze SET { key } = { as_string(value) } WHERE id = {id_arkusza}")
    else:
        print("Nie ma linków dla", rootpath + "!")


def stworz_arkusz(rok, opis):
    query(
        f"INSERT INTO arkusze (rok, opis) VALUES ({as_string(rok)}, {as_string(opis)});")
    return arkusz_istnieje(as_string(rok+" "+opis))


def przesledz_arkusze(rootpath):
    for rok in os.listdir(rootpath):
        if os.path.isfile(rootpath+rok):
                continue
        rok_path = rootpath+rok+"\\"
        for opis in os.listdir(rok_path):
            if os.path.isfile(rok_path+opis):
                continue
            opis_path = rok_path+opis+"\\"
            id_arkusza = arkusz_istnieje(as_string(rok+" "+opis))
            if not id_arkusza:
                id_arkusza = stworz_arkusz(rok, opis)
            przesledz_zadania(opis_path, id_arkusza)
            # sprawdz_linki(opis_path, id_arkusza)

def lokal():
    global db
    db = _mysql.connect(host="localhost", user="root",
                        charset='utf8mb4', conv=orig_conv)

def remote():
    global db, ftps
    host = os.getenv("INF_HOST")
    user = os.getenv("INF_USER")
    passwd = os.getenv("INF_PASSWD")
    db_name = os.getenv("INF_DBNAME")

    db = _mysql.connect(host=host, user=user, passwd=passwd, db=db_name,
                        charset='utf8mb4', conv=orig_conv)

    ftpuser = os.getenv("INF_FTPUSER")
    ftppass = os.getenv("INF_FTPPASS")

    ftps = FTP_TLS(host)
    ftps.encoding = "utf-8"
    ftps.login(ftpuser, ftppass)   
    ftps.prot_p()
    ftps.cwd("public_html")

orig_conv = converters.conversions
db = None
ftps = None

# !!!!!!!!!! #
lokal()
# remote()
# !!!!!!!!!! #

stworz_baze()

stworz_tabele_arkusze()
stworz_tabele_zadania()
stworz_tabele_tagi()
stworz_tabele_uploads()

website_root = os.getenv("INF_LOCALPATH")
rootpath = "pliki-arkusze\\"
przesledz_arkusze(website_root+rootpath)
