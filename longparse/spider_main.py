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
	def craw(self,root_url,key):  
		html_cont = self.downloader.download(root_url)  
		new_datas  = self.parser.parse_main(html_cont)
		count = 0; 
		for new_data in new_datas:
			count+=1
			
			try:
				print 'craw %d:%s' % (count,new_data['url'])
				if(self.outputer.check_unique(self.root+new_data['url'])):
					 
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
				else:
					print "This one has exsist!"+new_data['url']
			except Exception, e:
				print "craw faild"
				print e
			
		self.outputer.output_sql(key)
		 
if __name__=="__main__":
	url_Arr = {'69':'http://www.ilongterm.com/news/act-newlist__typeid-69.html','70':'http://www.ilongterm.com/news/act-newlist__typeid-70.html','71':'http://www.ilongterm.com/news/act-newlist__typeid-71.html'}
	for i in url_Arr:
		obj_spider = SpiderMain()
		obj_spider.craw(url_Arr[i],i)
		time.sleep(3)