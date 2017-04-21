#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QWidget,QApplication,QPushButton,QHBoxLayout,QVBoxLayout) 
from PyQt5.QtGui import QIcon,QFont

class Example(QWidget):
	def __init__(self):
		super(Example,self).__init__()
		self.initUI()
	def initUI(self):
		
		okButton = QPushButton('Ok')
		cancelButton = QPushButton('Cancel')

		hbox = QHBoxLayout()
		hbox.addStretch(1)
		hbox.addWidget(okButton)
		hbox.addWidget(cancelButton)

		vbox = QVBoxLayout()
		vbox.addStretch(1)
		vbox.addLayout(hbox)

		self.setLayout(vbox)

		self.setGeometry(300,300,300,220)
		self.setWindowTitle('Hbox')
		self.setWindowIcon(QIcon('web.png'))
		self.show()

    
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())