#! /usr/bin/python
#-*- coding:UTF-8 -*-
import url_manager,html_download,html_parser,html_output
import time
class SpiderMain(object):
	def __init__(self):
		self.urls       = url_manager.UrlManager()
		self.downloader = html_download.HtmlDownload()
		self.parser     = html_parser.HtmlParser()
		self.outputer   = html_output.OutPut()
		self.root       = 'http://www.ilongterm.com'
	def craw(self,root_url):  
		html_cont = self.downloader.download(root_url)  
		new_datas  = self.parser.parse_main(html_cont) 
		count = 0; 
		for new_data in new_datas:
			count+=1
			try: 
				print 'craw %d:%s' % (count,new_data['url']) 
				new_tmp = {}
				new_tmp['title'] = new_data['title']
				new_tmp['url'] = self.root+new_data['url']
				new_tmp['img'] = new_data['img']
				new_tmp['date'] = new_data['date']
				new_tmp['tag'] = new_data['tag']  
				new_tmp['des'] = new_data['des'] 
				html_cont = self.downloader.download(new_tmp['url']) 
				img_Arr,new_tmp['body'] = self.parser.parse(new_tmp['url'],html_cont) 
				self.downloader.down_img(self.root,new_tmp['img'])
				self.downloader.down_imgs(self.root,img_Arr)
				self.outputer.collect_data(new_tmp) 
			except Exception, e:
				print "craw faild"
				print e

		#self.outputer.output_html() 
		self.outputer.output_sql()
		 
if __name__=="__main__":
	for i in range(1,2):
		root_url = "http://www.ilongterm.com/news/pg-"+str(i)+".html"
		obj_spider = SpiderMain()
		obj_spider.craw(root_url)
		time.sleep(3)