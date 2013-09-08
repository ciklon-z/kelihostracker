#This is the python backend that checks DNS of domains in Array
import socket
import string
import datetime
from cymruwhois import Client
from async_dns import AsyncResolver
<<<<<<< HEAD
=======
with open('db\active.db') as f:
    content = f.readlines()
ar = AsyncResolver([content])
>>>>>>> 0e94139c1735c5c773f96b1d0b6dc95821cce300
while (True): 
	with open('db/active.db') as f:
		content = f.readlines()
	myList = [i.split('|')[0] for i in content] 
	ar = AsyncResolver(myList)
	resolved = ar.resolve()
	for host, ip in resolved.items():	
			if ip is None:
				print "%s could not be resolved." % host
			else: 
				if ip not in open('db/full.db').read():
					print "%s resolved to %s" % (host, ip)
					c=Client()
 					try:
						dnsrev = socket.gethostbyaddr(ip)
					except Exception, e:
            					print "IP: %s, DNS lookup error: %s" % (ip, e)
						dnsrev = ['Not Found', 'Not Found']
					now = datetime.datetime.now()
					date = now.strftime("%d %b %Y %H:%M:%S")
					try:
						r=c.lookup(ip)
					except Exception, e:
            					print "IP: %s, lookup error: %s" % (ip, e)
						r.asn = 'Not Found'
						r.owner = 'Not Found'
						r.cc = 'Not Found'
					with open("db/full.db", "a") as myfile:
						myfile.write(ip + '|' + str(dnsrev[0]) + '|' + r.asn + '|' + r.owner + '|' + r.cc + '|' + date + '\n')

