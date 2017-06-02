#! /usr/bin/python
#-*- coding:UTF-8 -*-
from bs4 import BeautifulSoup
import html_download
import re
import urlparse
import demjson
class HtmlParser(object): 
	def __init__(self):
		self.downloader = html_download.HtmlDownload()
	def _get_new_urls(self,page_url,soup):
		new_urls = set() 
		# /view/13.html
		#links = soup.find_all('a',href=re.compile(r"/item/\.*\."))
		for link in links: 
			new_url = link['href']
			new_full_url = urlparse.urljoin(page_url,new_url)
			new_urls.add(new_full_url)
		# 使用Pattern匹配文本，获得匹配结果，无法匹配时将返回None
		#match = pattern.match('<key>xxx/duobaojiemian_L/yangpizi.png</key>')
		return new_urls 
		
		
	def _get_main_urls(self,soup): 
		data = []
		datas = demjson.decode(soup)
		error_code = datas['showapi_res_code']
		if(error_code==0):
			links = datas['showapi_res_body']
			print(links)
			#for link in links:
				#print(link['weixin_url'])
		
		return data
	def _get_new_data(self,page_url,soup):
		
		data_imgs = set()
		link = soup.find('div',id="contentbody")
		i=0
		j=0
		for p in link.find_all('p'):
			i=i+1
		for p in link.find_all('p'):
			j=j+1
			if(j==1):
				p.extract()
			if(j>(i-1)):
				p.extract()
		#去除超链接
		for s in link.find_all('a'):
			del s['href']
			del s['target']
			del s['style']
		#去除超链接
		for ifra in link.find_all('iframe'):
			pass#del ifra['width']
			pass #del ifra['height']
		#取得页面面图片地址
		try:
			img_Arr = link.find_all('img')
			for img in img_Arr: 
				#img_one = img['src']
				#data_imgs.add(img_one)
 				img['src'] = self.downloader.down_img_free(img['src'])
		except Exception,e:
			print "This page have no image"
			print e 
		root = 'http://pics.shenchuang.com'
		return data_imgs,str(link).replace(root,'')
	def parse_main(self,html_cont):  
		soup = BeautifulSoup(html_cont,'html.parser',from_encoding='UTF-8') 
		new_data = self._get_main_urls(soup)
		return new_data
	def parse(self,page_url,html_cont):
		if page_url is None or html_cont is None:
			return
		soup = BeautifulSoup(html_cont,'html.parser',from_encoding='UTF-8')  
		img_Arr,new_data = self._get_new_data(page_url,soup)
		return img_Arr,new_data