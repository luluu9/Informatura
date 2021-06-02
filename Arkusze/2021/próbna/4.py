galerie = []
with open("Dane_2103/galerie.txt") as f:
    for line in f.readlines():
        galerie.append(line.strip()) # strip() oczyszcza ciąg znaków z białych znaków (jak nowa linia \n)

# ZAD 1 #
print("Zadanie 1.")
kraje = {} 
for galeria in galerie:
    kraj = galeria.split()[0] # split() tworzy tablicę z wyrazów rozdzielonych spacjami
    if not kraj in kraje: 
        kraje[kraj] = 1
    else:
        kraje[kraj] += 1

for kraj, ile in kraje.items():
    print(kraj, ile)


# ZAD 2 A) #
print("-------------------")
print("Zadanie 2. a)")
pow_galerii = {}
for galeria in galerie:
    miasto = galeria.split()[1]
    powierzchnie = galeria.split()[2:]
    lokale = 0
    calk_pow = 0
    for i in range(0, 140, 2): # iteracja od zera do 140 krokami po 2, np. 0, 2, 4...
        if int(powierzchnie[i]) == 0:
            break
        calk_pow += int(powierzchnie[i])*int(powierzchnie[i+1])
        lokale += 1
    pow_galerii[miasto] = calk_pow
    print(miasto, calk_pow, lokale)

# ZAD 2 B) #
# items() zwraca słownik w postaci [(klucz1, wartość1), (klucz2, wartość2)...]
# key=lambda lokal: lokal[1] oznacza, że za wartość do sortowania wybieramy wartość lokalu
# a wartością lokalu u nas jest jego powierzchnia całkowita (wyliczyliśmy to w podpunkcie A)
print("-------------------")
print("Zadanie 2. b)")
najw = sorted(pow_galerii.items(), key=lambda lokal: lokal[1])[0] 
najm = sorted(pow_galerii.items(), key=lambda lokal: lokal[1])[-1]
print(najw[0], najw[1])
print(najm[0], najm[1])

# ZAD 3 #
print("-------------------")
print("Zadanie 3.")
unik_lok_gal = {} # unikalne lokale galerii
for galeria in galerie:
    miasto = galeria.split()[1]
    powierzchnie = galeria.split()[2:]
    unikalne_lokale = set() # set przechowuje tylko unikalne wartości
    for i in range(0, 140, 2):
        if int(powierzchnie[i]) == 0:
            break
        pow_lokalu = int(powierzchnie[i])*int(powierzchnie[i+1])
        unikalne_lokale.add(pow_lokalu)
    unik_lok_gal[miasto] = len(unikalne_lokale) # ile jest unikalnych lokali

najw = sorted(unik_lok_gal.items(), key=lambda lokal: lokal[1])[0] 
najm = sorted(unik_lok_gal.items(), key=lambda lokal: lokal[1])[-1]
print(najw[0], najw[1])
print(najm[0], najm[1])