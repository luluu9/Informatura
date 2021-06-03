dane = ""
with open("DANE_PR2/liczby.txt") as f:
	dane = f.readlines()

zwoj = 0
for line in dane:
	line = line.strip()
	z = 0
	j = 0
	for char in line:
		if char == '0':
			z += 1
		elif char == '1':
			j += 1
	if z > j:
		zwoj += 1
print("4.1. Wiecej zer od jedynek ma", str(zwoj), "liczb")

d8 = 0
d2 = 0
for line in dane:
	line = line.strip()
	if int(line, 2) % 2 == 0:
		d2 += 1
	if int(line, 2) % 8 == 0:
		d8 += 1
print("4.2 Liczb podzielnych przez 2:", d2, "\nLiczb podzielnych przez 8:", d8)

i = 1
najw = 0
najm = 999999
najw_i = 0
najm_i = 0
for line in dane:
	line = line.strip()
	wd = int(line, 2)
	if wd > najw:
		najw = wd
		najw_i = i
	if wd < najm:
		najm = wd
		najm_i = i
	i += 1
print("4.3. Indeks najmniejszej liczby:", najm_i, "\nIndeks najwiekszej liczby:", najw_i)
