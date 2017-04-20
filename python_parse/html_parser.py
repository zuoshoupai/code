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
		cont = soup.find('ul',class_="list_ul")
		links = cont.find_all('li') 
		for link in links: 
			res_data = {} 
			res_data['title'] = link.find('h3').find('a').get_text()
			res_data['url']   = link.find('h3').find('a')['href']
			res_data['des']   = link.find('p',class_="list_info").get_text()
			res_data['date']  = link.find('p',class_="news_time").find('span').get_text()
			#标签
			cates  = link.find('p',class_="list_tag").find_all('span')
			cate_strs = ''
			for cate in cates:
				cate_str = cate.find('a').get_text()
				cate_strs = cate_strs+cate_str+','
			res_data['tag'] = cate_strs[:-1]
			try:
				res_data['img']   = link.find('img')['src']
			except:
				res_data['img']   = ''
			data.append(res_data) 
		return data
	def _get_new_data(self,page_url,soup):
		link = soup.find('div',class_="news_text")
		# 将正则表达式编译成Pattern对象 
		link.find('div',class_="news_tag").extract()

		#tag.clear()
		#link=re_style.sub('',link)#去掉style  
 		return link
	def parse_main(self,html_cont):  
		soup = BeautifulSoup(html_cont,'html.parser',from_encoding='UTF-8') 
		new_data = self._get_main_urls(soup)
		return new_data
	def parse(self,page_url,html_cont):
		if page_url is None or html_cont is None:
			return
		soup = BeautifulSoup(html_cont,'html.parser',from_encoding='UTF-8')  
		new_data = self._get_new_data(page_url,soup)
		return new_data