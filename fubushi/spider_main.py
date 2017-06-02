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
			print(new_data)
			count+=1
			try: 
				print(count)
				#print 'craw %d:%s' % (count,new_data['url']) 
				self.outputer.collect_data(new_data) 
			except Exception, e:
				print "craw faild"
				print e

		self.outputer.output_html()
		#self.outputer.output_sql()
if __name__=="__main__":
	for i in range(1,2):
		root_url = "http://bbs.fobshanghai.com/qun.php?subcatid=576"
		obj_spider = SpiderMain()
		obj_spider.craw(root_url)
		time.sleep(1)