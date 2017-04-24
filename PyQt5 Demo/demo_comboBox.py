#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QWidget,QLabel,QComboBox,QApplication)
class Example(QWidget):
	def __init__(self):
		super(Example,self).__init__()
		self.initUI()
	def initUI(self):
		self.lb1 = QLabel("Ubuntu",self)
		combo = QComboBox(self)
		combo.addItem("Ubuntu")
		combo.addItem("Mandriva")
		combo.addItem("Fedora")
		combo.addItem("Arch")
		combo.addItem("Gentoo")

		combo.move(50,50)
		self.lb1.move(50,150)

		combo.activated[str].connect(self.onActivated)

		 
		
		self.setGeometry(300,300,280,170)
		self.setWindowTitle('Splitter')
		self.show() 
	
	def onActivated(self,text):
		self.lb1.setText(text)
		self.lb1.adjustSize()		
    
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())