#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QWidget,QGridLayout,QPushButton,QApplication) 
from PyQt5.QtGui import QIcon,QFont

class Example(QWidget):
	def __init__(self):
		super(Example,self).__init__()
		self.initUI()
	def initUI(self):
		
		grid = QGridLayout()
		self.setLayout(grid)
		
		self.setGeometry(300,300,300,220)
		self.setWindowTitle('Hbox')
		self.setWindowIcon(QIcon('web.png'))
		self.show()

    
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())