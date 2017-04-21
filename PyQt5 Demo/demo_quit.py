#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QApplication,QWidget,QToolTip,QPushButton,QApplication)
from PyQt5.QtGui import QIcon,QFont
from PyQt5.QtCore import QCoreApplication

class Example(QWidget):
	def __init__(self):
		super(Example,self).__init__()
		self.initUI()
	def initUI(self):
		qbtn = QPushButton('Quit',self)
		qbtn.clicked.connect(QCoreApplication.instance().quit)
		qbtn.resize(qbtn.sizeHint())
		qbtn.move(50,50)

		self.setGeometry(300,300,300,220)
		self.setWindowTitle('Quit')
		self.setWindowIcon(QIcon('web.png'))
		self.show()

    
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())