#! /usr/bin/python
#-*- coding:UTF-8 -*-
import re,os
import time
import urllib,urllib2
class HtmlDownload(object): 
	def download(self,url):
		if url is None:
			return None
		user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
		headers = { 'User-Agent' : user_agent }
		request = urllib2.Request(url,headers = headers)
		response = urllib2.urlopen(request)
		if response.getcode()!=200:
			return None
		return response.read()
	def download_gbk(self,url):
		if url is None:
			return None
		user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
		headers = { 'User-Agent' : user_agent }
		request = urllib2.Request(url,headers = headers)
		response = urllib2.urlopen(request)
		if response.getcode()!=200:
			return None
		html = response.read()
		return html.decode('gbk').encode('utf8')
	def down_imgs(self,urls):
		root = 'http://pics.shenchuang.com'
		for url in urls:
			filename = url.replace(root,'')
 			filename = filename.replace('wx_fmt=jpeg','')
 			filename = filename.replace('?','/1.jpg')
 			new_url  = '/home/www/stpaulsfriends/public'+filename
	 		dir_name,filename = os.path.split(new_url)
	 		if not os.path.exists(dir_name):
	 			os.makedirs(dir_name)
	 		urllib.urlretrieve(url,new_url)
	 		print new_url
 	def down_img(self,url):
 		root = 'http://thumb.shenchuang.com'
 		if url == '':
 			print 'This picture is empty!'
 			return
 		filename = url.replace(root,'')
 		new_url  = '/home/www/stpaulsfriends/public/image/show'+filename
 		dir_name,filename = os.path.split(new_url)
 		try:
	 		if not os.path.exists(dir_name):
	 			os.makedirs(dir_name)
	 		urllib.urlretrieve(url,new_url)
		except Exception,e:
	 		print e
 		print new_url
 	def down_img_free(self,url,parm=''):
 		print(url)
 		
 		if url == '':
 			print 'This picture is empty!'
 			return
 		filename  =  str(int(time.time()*1000)) +'.jpeg'
 		new_url  = '/home/www/stpaulsfriends/public/'+parm+'upload/'+filename
 		call_url = '/upload/'+filename
 		dir_name,filename = os.path.split(new_url)
 		try:
	 		if not os.path.exists(dir_name):
	 			os.makedirs(dir_name)
	 		urllib.urlretrieve(url,new_url)
		except Exception,e:
	 		print e
 		return call_url
         
