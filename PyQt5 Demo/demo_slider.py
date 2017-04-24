#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QWidget,QSlider,QLabel,QApplication)
from PyQt5.QtCore import Qt
from PyQt5.QtGui import QPixmap
class Example(QWidget):
	def __init__(self):
		super(Example,self).__init__()
		self.initUI()
	def initUI(self):
		
		sld = QSlider(Qt.Horizontal,self)
		sld.setFocusPolicy(Qt.NoFocus)
		sld.setGeometry(30,40,100,30)
		sld.valueChanged[int].connect(self.changeValue)

		self.label = QLabel(self)
		self.label.setPixmap(QPixmap('mute.png'))
		self.label.setGeometry(160,40,80,30)




		#self.move(300,150)
		self.setGeometry(300,300,290,150)
		self.setWindowTitle('CheckBox')
		self.show()
	def changeValue(self,value):
		if value == 0:
			self.label.setPixmap(QPixmap('mute.gif'))
		elif value >0 and value <= 30:
			self.label.setPixmap(QPixmap('min.gif'))
		elif value>30 and value <80:
			self.label.setPixmap(QPixmap('med.gif'))
		else:
			self.label.setPixmap(QPixmap('max.gif'))

			
    
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())