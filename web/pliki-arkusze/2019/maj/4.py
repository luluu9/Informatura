def silnia(n):
	if n == 0:
		return 1
	return n*silnia(n-1)

def dzielniki(n):
	dziel = []
	for i in range(1, n//2):
		if n%i == 0:
			dziel.append(n//i)
	return dziel

dane = ""
with open("DANE_PR2\\liczby.txt") as f:
	dane = f.readlines()

powers = [3**i for i in range(0, 11)]
am = 0
for line in dane:
	line = line.strip()
	if int(line) in powers:
		am += 1

print("4.1.", am)

g = []
for line in dane:
	line = line.strip()
	if int(line) == sum([silnia(int(n)) for n in line]):
		g.append(int(line))

print("4.2.", g)

ciag = []
for line in dane:
	line = line.strip()
	ciag.append(int(line))

ws_dziel = [] # wszystkie dzielniki
for line in dane:
	line = line.strip()
	ws_dziel.append(dzielniki(int(line)))


naj_dl = 0 # najwieksza dlugosc
pierw = None # pierwszy dzielnik
nwd = None
for i in range(0, len(ws_dziel)): # dzielniki
	for dziel in ws_dziel[i]: # dzielnik
		dl = 0
		for j in range(i, len(ws_dziel)):
			if dziel in ws_dziel[j]:
				dl += 1
			else:
				break
			if dl > naj_dl:
				nwd = dziel
				pierw = ciag[i]
				naj_dl = dl

print("4.3.", pierw, naj_dl, nwd)


	
