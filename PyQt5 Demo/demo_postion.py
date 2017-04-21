#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QWidget,QApplication,QLabel) 
from PyQt5.QtGui import QIcon,QFont

class Example(QWidget):
	def __init__(self):
		super(Example,self).__init__()
		self.initUI()
	def initUI(self):
		
		lb1 = QLabel('Zetcode',self)
		lb1.move(15,10)

		lb2 = QLabel('Tutorials',self)
		lb2.move(35,40)

		lb3 = QLabel('For programmers',self)
		lb3.move(55,70)

		for i in range(6):
			lb = QLabel(str(i),self)
			lb.move(i*10+10,i*5+20)

		self.setGeometry(300,300,300,220)
		self.setWindowTitle('Position')
		self.setWindowIcon(QIcon('web.png'))
		self.show()

    
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())