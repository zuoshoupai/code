#! /usr/bin/python
#-*- coding:UTF-8 -*-
import re,os
import time
import urllib,urllib2;
class HtmlDownload(object): 
	def download(self,url):
		if url is None:
			return None
		user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
		headers = { 'User-Agent' : user_agent }
		value={
	    'showapi_appid':'33294',
	    'showapi_sign':'efdc342a7d8848e09c9fc6ad945a0c10',
	    'type':'41'
		}
		postdata=urllib.urlencode(value)#对value进行编码，转换为标准编码#
		request = urllib2.Request(url,postdata,headers = headers)
		response = urllib2.urlopen(request)
		if response.getcode()!=200:
			return None
		return response.read().encode('utf-8');
	def down_imgs(self,urls):
		root = 'http://pics.shenchuang.com'
		for url in urls:
			filename = url.replace(root,'')
 			filename = filename.replace('wx_fmt=jpeg','')
 			filename = filename.replace('?','/1.jpg')
 			new_url  = 'D:/wamp/www/tipask/public'+filename
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
 		new_url  = 'D:/wamp/www/tipask/public/image/show'+filename
 		dir_name,filename = os.path.split(new_url)
 		try:
	 		if not os.path.exists(dir_name):
	 			os.makedirs(dir_name)
	 		urllib.urlretrieve(url,new_url)
		except Exception,e:
	 		print e
 		print new_url
 	def down_img_free(self,url):
 		print(url)
 		
 		if url == '':
 			print 'This picture is empty!'
 			return
 		filename  =  str(int(time.time()*1000)) +'.jpeg'
 		new_url  = 'D:/wamp/www/tipask/public/upload/'+filename
 		call_url = '/upload/'+filename
 		dir_name,filename = os.path.split(new_url)
 		try:
	 		if not os.path.exists(dir_name):
	 			os.makedirs(dir_name)
	 		urllib.urlretrieve(url,new_url)
		except Exception,e:
	 		print e
 		return call_url
         
