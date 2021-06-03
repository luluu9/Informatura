x = 10
A = [5, 99, 3, 7, 111, 13, 4, 24, 4, 8]

w=0
il=0
def liniowe():
    for i in range(0, x):
        global w, il
        il += 1
        if A[i]%2==0:
            w=A[i]
            break

def logarytmiczne(poczatek, koniec):
    global w, il
    il += 1
    indeks_srodkowy = (poczatek+koniec)//2
    print(poczatek, koniec, indeks_srodkowy)
    if koniec-poczatek==0:
        w = A[indeks_srodkowy]
    elif A[indeks_srodkowy]%2==0:
        logarytmiczne(poczatek, indeks_srodkowy)
    else:
        logarytmiczne(indeks_srodkowy+1, koniec)


liniowe()
print("LINIOWA",w, il)
w=0
il=0
poczatek = 0
koniec = x-1
logarytmiczne(poczatek, koniec)
print("LOG", w, il)