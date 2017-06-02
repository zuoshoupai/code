#_*_ coding:UTF-8 _*_
import cookielib
import urllib2
import sys
reload(sys)
sys.setdefaultencoding('utf-8')

cookie=cookielib.MozillaCookieJar()
cookie.load('cookies.txt',ignore_expires=True,ignore_discard=True)
req=urllib2.Request('http://bbs.fobshanghai.com/qun.php?subcatid=576')
opener=urllib2.build_opener(urllib2.HTTPCookieProcessor(cookie))
urllib2.install_opener(opener)
response=urllib2.urlopen(req)
print response.read()