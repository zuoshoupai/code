#! /usr/bin/python
#-*- coding:UTF-8 -*-
from bs4 import BeautifulSoup
import re
import urlparse

class HtmlParser(object): 
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
		# /view/13.html
		cont = soup.find('form',id="sendqunform").find('div',class_="outsortlist")
		links = cont.find_all('div') 
		for link in links: 
			li = 0
			res_data = {}
			
			for p in link.find_all('li'):
				
				li = li+1

				if(li==1):
					res_data['user'] = p.find('a',class_="font_blue12b").get_text() #用户名
				
				if(li==2):
					res_data['type'] = p.get_text() #所属行业
					area = ''
					for a in p.find_all('a'):
						area = a.get_text()+area+','
					res_data['area'] = area #工作地
				if(li==3):
					res_data['company'] = p.get_text() #公司名称
				if(li==4):
					res_data['qq_num'] = p.get_text() #qq号码
				if(li==5):
					res_data['email'] = p.get_text() #邮箱
			data.append(res_data) 
		return data
	def _get_new_data(self,page_url,soup):
		
		data_imgs = set()
		link = soup.find('div',class_="entry-content")
		link.find('div').extract()
		#去除超链接
		for s in link.find_all('a'):
			del s['href']
			del s['target']
			del s['style']
		#取得页面面图片地址
		try:
			img_Arr = link.find_all('img')
			for img in img_Arr: 
				img_one = img['src']
				data_imgs.add(img_one)
		except Exception,e:
			print "This page have no image"
			print e 
		root = 'http://www.yjhome.net'
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