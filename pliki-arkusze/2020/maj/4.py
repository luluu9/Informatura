lines = ""
with open("Dane_PR2/pary.txt") as f:
    lines = f.readlines()

nums = []
strings = []

for line in lines:
    line.strip()
    num, string = line.split()
    nums.append(int(num))
    strings.append(string)


### 4.1 ###
print("\n4.1")
from math import sqrt
prime_nums = []
for num in range(3, 100): # zakres od 3, bo 2 jest liczbą parzystą
    if all(num%i!=0 for i in range(2, int(sqrt(num))+1)):
       prime_nums.append(num)

def is_prime(num):
    return True if num in prime_nums else False

for num in nums:
    if num % 2 == 0:
        for i in range(3, num//2+1): # zakres od 3, bo 2 jest liczbą parzystą, a do num//2+1, ponieważ jeśli sprawdzalibyśmy dalej, to odtwarzalibyśmy sprawdzanie od tyłu
            if is_prime(i) and is_prime(num-i):
                print(num, i, num-i)
                break

### 4.2 ###
print("\n4.2")
for string in strings:
    current_word = string[0]
    longest_word = string[0]
    for char in string[1:]:
        if char == current_word[-1]:
            current_word += char
        else:
            current_word = char
        if len(current_word) > len(longest_word):
                longest_word = current_word
    print(longest_word, len(longest_word))


### 4.3 ###
print("\n4.3")
pairs = []
for num, string in zip(nums, strings):
    if num == len(string):
        pairs.append((num, string))

smallest_pair = sorted(pairs)[0]
print(smallest_pair[0], smallest_pair[1])