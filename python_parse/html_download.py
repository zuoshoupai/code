#! /usr/bin/python
#-*- coding:UTF-8 -*-
import re,os
import urllib,urllib2;
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
	def down_imgs(self,root,urls):
		for url in urls:
			full_url = root+url 
	 		new_url  = 'D:\\wamp\www\\tipask-3.2.1\\public'+url
	 		dir_name,filename = os.path.split(new_url)
	 		if not os.path.exists(dir_name):
	 			os.makedirs(dir_name)
	 		urllib.urlretrieve(full_url,new_url)
	 		print new_url
 	def down_img(self,root,url):
 		full_url = root+url 
 		new_url  = 'D:\\wamp\www\\tipask-3.2.1\\public\\image\\show'+url
 		dir_name,filename = os.path.split(new_url)
 		if not os.path.exists(dir_name):
 			os.makedirs(dir_name)
 		urllib.urlretrieve(full_url,new_url)
 		print new_url
         
