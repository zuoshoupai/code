#! /usr/bin/python
#-*- coding:UTF-8 -*-
import re,os
import urllib,urllib2;
import cookielib
class HtmlDownload(object): 
	def download(self,url):
		
		cookie=cookielib.MozillaCookieJar()
		cookie.load('cookies.txt',ignore_expires=True,ignore_discard=True)
		req=urllib2.Request(url)
		opener=urllib2.build_opener(urllib2.HTTPCookieProcessor(cookie))
		urllib2.install_opener(opener)
		response=urllib2.urlopen(req)
		return response.read()
	def down_imgs(self,root,urls):
		for url in urls:
			filename = url.replace(root,'')
 			new_url  = '/home/www/tipask/public'+filename
	 		dir_name,filename = os.path.split(new_url)
	 		if not os.path.exists(dir_name):
	 			os.makedirs(dir_name)
	 		urllib.urlretrieve(url,new_url)
	 		print new_url
 	def down_img(self,root,url):
 		if url == '':
 			print 'This picture is empty!'
 			return
 		filename = url.replace(root,'')
 		new_url  = '/home/www/tipask/public/image/show'+filename
 		dir_name,filename = os.path.split(new_url)
 		try:
	 		if not os.path.exists(dir_name):
	 			os.makedirs(dir_name)
	 		urllib.urlretrieve(url,new_url)
		except Exception,e:
	 		print e
 		print new_url
         
