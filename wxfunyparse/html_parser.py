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
		datas = demjson.decode(soup.encode('utf-8'))
		error_code = datas['showapi_res_code']
		if(error_code==0):
			links = datas['showapi_res_body']['pagebean']['contentlist']
			for link in links:
				res_data = {} 
				res_data['title'] = link['name']
				res_data['url']   = link['weixin_url']
				res_data['des']   = link['text']
				res_data['date']  = link['create_time']
				res_data['tag']   = '搞笑'
				data.append(res_data)
		return data
	def _get_new_data(self,page_url,soup):
		data_imgs = set()
		#print(soup)
		link = soup.find('article')
		link.find('div',class_="list-user-author").extract()
		link.find('div',class_="item-tool").extract()
		padding = link.find('div',class_="x-video-p")
		del padding['style']
		#去除超链接
		for s in link.find_all('a'):
			del s['href']
			del s['target']
			del s['style']
		img  = link.find('img')
 		img_new = self.downloader.down_img_free(img['src'],'image/show/')
 		link.find('video')['poster'] = img['src']
 		link.find('div',class_="x-video-poster").extract()
		return img_new,link
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