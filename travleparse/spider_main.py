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
		html_cont = self.downloader.download_gbk(root_url)
		new_datas  = self.parser.parse_main(html_cont)
		count = 0; 
		for new_data in new_datas:
			count+=1
			if(self.outputer.check_unique(new_data['url'])):
				try: 
					print 'craw %d:%s' % (count,new_data['url']) 
					new_tmp = {}
					new_tmp['title'] = new_data['title']
					new_tmp['url'] =   new_data['url']
					new_tmp['date'] = new_data['date']
					new_tmp['tag'] = new_data['tag']  
					new_tmp['des'] = new_data['des'] 
					
					html_cont = self.downloader.download_gbk(new_data['url'])

					img_Arr,new_tmp['body'] = self.parser.parse(new_data['url'],html_cont)
					if(len(str(new_tmp['body']))>200):
						new_tmp['img'] = self.downloader.down_img_free(new_data['img'],'image/show/')
						self.outputer.output_sql_single(new_tmp)
					print('ok')
				except Exception, e:
					print "craw faild"
					print e
			else:
				print "This one has exsist!"+new_data['url']
if __name__=="__main__":
	for i in range(1,2):
		root_url = "http://travel.163.com/special/00067VEJ/newsdatas_travel.js?callback=data_callback"
		obj_spider = SpiderMain()
		obj_spider.craw(root_url)
		time.sleep(30)