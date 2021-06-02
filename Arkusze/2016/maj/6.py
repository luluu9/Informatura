def szyfr(ciag, k):
    nowy_ciag = ""
    for lit in ciag:
        nowy_ord = 65+(ord(lit)-65+k) % 26
        nowy_ciag += chr(nowy_ord)
    return nowy_ciag


def odszyfr(ciag, k):
    nowy_ciag = ""
    for lit in ciag:
        nowy_ord = 65+(ord(lit)-65-k) % 26
        nowy_ciag += chr(nowy_ord)
    return nowy_ciag


def szukaj(ciag, ciag2):
    def zdobadz_klucz(znak, znak2):
        if ord(znak2) < ord(znak):
            return 26-(ord(znak)-ord(znak2))
        else:
            return abs(ord(znak2)-ord(znak))
    roz = zdobadz_klucz(ciag[0], ciag2[0])
    if ciag2 != szyfr(ciag, roz):
        return False
    return True


with open("wyniki_6_1.txt", "w") as wyjscie:
    with open("DANE_PR2/dane_6_1.txt") as wejscie:
        for linia in wejscie:
            if not linia:
                continue
            linia = linia.strip()
            wyjscie.write(szyfr(linia, 107)+"\n")

with open("wyniki_6_2.txt", "w") as wyjscie:
    with open("DANE_PR2/dane_6_2.txt") as wejscie:
        for linia in wejscie:
            if not linia:
                continue
            linia = linia.strip()
            try:
                ciag, k = linia.split()
            except:
                k = 0
            wyjscie.write(odszyfr(ciag, int(k))+"\n")

with open("wyniki_6_3.txt", "w") as wyjscie:
    with open("DANE_PR2/dane_6_3.txt") as wejscie:
        for linia in wejscie:
            linia = linia.strip()
            ciag1, ciag2 = linia.split()
            if not szukaj(ciag1, ciag2):
                wyjscie.write(ciag1+"\n")
