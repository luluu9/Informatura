T = [1, 3, 5, 6 ,7, 8]

def rek(x, p, k):
    if (p < k):
        s = (p+k)//2
        if T[s] >= x:
            return rek(x, p, s)
        else:
            return rek(x, s+1, k)
    else:
        if T[p] == x:
            return p
        else:
            return -1
 
print(rek(6, 0, 5))