#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QWidget,QLabel,QLineEdit,QApplication) 
class Example(QWidget):
	def __init__(self):
		super(Example,self).__init__()
		self.initUI()
	def initUI(self):
		
		self.lb1 = QLabel(self)
		qle = QLineEdit(self)
		qle.move(60,100)
		self.lb1.move(60,40)
		qle.textChanged[str].connect(self.onChanged)
		
		self.setGeometry(300,300,280,170)
		self.setWindowTitle('QLineEdit')
		self.show()
	def onChanged(self,text):
		self.lb1.setText(text)
		self.lb1.adjustSize() 
	
			
    
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())