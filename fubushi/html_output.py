#! /usr/bin/python
# -*-coding:utf-8 -*-
#encoding=utf-8   
import sys   
import time
import MySQLdb   
reload(sys)   
sys.setdefaultencoding('utf-8')   
class OutPut(object):
	def __init__(self):
		self.datas   = []
		self.host    = 'localhost'
		self.user    = 'root'
		self.passwd  = 'yin2016;'
		self.db      = 'tipask'

	def collect_data(self,data):
		if data is None:
			return 
		self.datas.append(data)
	def output_sql(self):
		try:
		    conn = MySQLdb.connect(host=self.host,user=self.user,passwd=self.passwd,db=self.db,charset='utf8')
		except Exception, e:
		    print e
		    sys.exit()

		# 获取cursor对象来进行操作
		cursor = conn.cursor()
		root = 'http://www.yjhome.net'
		ISOTIMEFORMAT='%Y-%m-%d %X'
		for data in self.datas: 
			url   = data['url']
			img   = str(data['img']).replace(root,'.')
			body  = MySQLdb.escape_string(str(data['body']))
			title = data['title'] 
			tag   = data['tag']
			des   = MySQLdb.escape_string(str(data['des']))
			#time  = data['date'] 
			time_date  =  time.strftime( ISOTIMEFORMAT, time.localtime( ) )
			sql_s = "select count(id) as count from ask_articles where url='%s'" % (url)
			cursor.execute(sql_s)
			data = cursor.fetchone()
			if(data[0]==0): 
				sql = "insert into ask_articles (user_id,url,logo,title,summary,content,created_at,status,category_id) values ('%s','%s','%s','%s','%s','%s','%s','%s','%s')" % ('1',url,img,title,des,body,time_date,'1','9')
				try: 
					cursor.execute(sql)
				except Exception, e:
					print url
					fout = open('errorlog.html','w')
					fout.write(url)
					fout.write(sql)
					fout.write("<br/>")
					print e  
			else:
				print "This one has exsist!"+url
		cursor.close()
		conn.close() 
	def output_html(self): 
		fout = open('output.html','w')
		fout.write('<html>')
		fout.write('<body>')
		fout.write('<table border=1 cellspacing=0>')
		fout.write('<tr>')
		fout.write('<td>用户名</td>')
		fout.write('<td>所属行业</td>')
		fout.write('<td>工作所在地</td>')
		fout.write('<td>公司名称</td>')
		fout.write('<td>qq号码</td>')
		fout.write('<td>邮箱</td>')
		fout.write('</tr>') 
		for data in self.datas:
			try:
				fout.write('<tr>')
				fout.write('<td>%s</td>' % data['user'].encode('utf-8'))
				fout.write('<td>%s</td>' % data['type'].encode('utf-8'))
				fout.write('<td>%s</td>' % data['area'].encode('utf-8'))
				fout.write('<td>%s</td>' % data['company'].encode('utf-8'))
				fout.write('<td>%s</td>' % data['qq_num'].encode('utf-8'))
				fout.write('<td>%s</td>' % data['email'])
				fout.write('</tr>') 
			except Exception, e:
				print "craw faild"
				print e
		fout.write('</table>')
		fout.write('</body>')
		fout.write('</html>')  