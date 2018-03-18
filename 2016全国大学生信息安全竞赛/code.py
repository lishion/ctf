str = "X8SY" #type:str
#str = flag
#str = "EW S4W WFU5LWV L1 T41SVW0 1M4 2S4L0W4KZ52 E5LZ LZW 9505KL4G 1X WVMUSL510 L1 9S7W S 810Y-8SKL50Y 592SUL 10 LZW 85NWK 1X UZ50WKW KLMVW0LK LZ41MYZ S 6150L8G-VWK5Y0WV TSK7WLTS88 UM445UM8M9 S0V S E5VW 4S0YW 1X KUZ118 TSK7WLTS88 241Y4S9K," KS5V KZ1W9S7W4. "LZ5K U1995L9W0L 9S47K S01LZW4 958WKL10W 50 LZW 0TS'K G1MLZ S0V TSK7WLTS88 VWNW8129W0L WXX14LK 50 UZ50S"


#print( ord(list(str)[i])-ord(list("flag")[i]) )
# l = list(str)
# ll = ""
# for item in l:
#     if item != " ":
#         o = ord(item)
#         if 65<=o and o<=90:
#             ll += chr(ord(item)+14)
#         else:
#             ll += chr(ord(item)+52)
#     else:
#          ll += " "
# print(ll)
# x:
# beijing- nba china and the  chinese ministry of education  announced monday
# 0:n 1:o 2:p 3:q 4:r 5:i 6:j 7:k  8:l  9:m

# a b c d e f g:y h i j k:s l:t m:u n:v o:w p:x r:y 

# S:a T:b U:c V:d W:e X:f Y:g Z:h 

#{ YK182V9ZUL9STU5V}
#  gsolpdmhctmabcid
#FLAG{GSOLPDMHCTMABCID}
file = open("data","r")
word_num = {}
for line in file:
    words = list(line)
    for word in words:
        if word in word_num:
            word_num[word] += 1
        else:
            word_num[word] = 1

print(sorted(word_num.items(),key=lambda item:item[1],reverse=True))
