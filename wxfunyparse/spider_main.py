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
	def craw(self,root_url):  
		html_cont = self.downloader.download(root_url)
		
		new_datas  = self.parser.parse_main(html_cont)
		
		count = 0; 
		for new_data in new_datas:
			count+=1
			try: 
				print 'craw %d:%s' % (count,new_data['url']) 
				if(self.outputer.check_unique(new_data['url'])):
					new_tmp = {}
					new_tmp['title'] = new_data['title']
					new_tmp['url']   = new_data['url']
					new_tmp['date'] = new_data['date']
					new_tmp['tag'] = new_data['tag']  
					new_tmp['des'] = new_data['des'] 
					html_cont = self.downloader.download(new_tmp['url'])
					img_Arr,new_tmp['body'] = self.parser.parse(new_tmp['url'],html_cont)
					new_tmp['img'] = img_Arr
					#self.downloader.down_img(new_tmp['img'])
					#self.downloader.down_imgs(img_Arr)
					self.outputer.output_sql_single(new_tmp)
					#self.outputer.collect_data(new_tmp) 
				else:
					print "This one has exsist!"+new_data['url']
			except Exception, e:
				print "craw faild"
				print e
		
if __name__=="__main__":
	for i in range(1,2):
		root_url = "http://route.showapi.com/255-1"
		obj_spider = SpiderMain()
		obj_spider.craw(root_url)
		time.sleep(30)