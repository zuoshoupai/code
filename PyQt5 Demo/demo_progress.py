#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QWidget,QProgressBar,QPushButton,QApplication)
from PyQt5.QtCore import QBasicTimer
class Example(QWidget):
	def __init__(self):
		super(Example,self).__init__()
		self.initUI()
	def initUI(self):
		self.pbar = QProgressBar(self)
		self.pbar.setGeometry(30,40,200,25)
		self.btn = QPushButton('Start',self)
		self.btn.move(40,80)
		self.btn.clicked.connect(self.doAction)

		self.btn1 = QPushButton('Init',self)
		self.btn1.move(180,80)
		self.btn1.clicked.connect(self.initAction)
		self.timer = QBasicTimer()
		self.step = 0




		#self.move(300,150)
		self.setGeometry(300,300,290,150)
		self.setWindowTitle('CheckBox')
		self.show()
	def timerEvent(self,e):
		if self.step >= 100:
			self.timer.stop()
			self.btn.setText('Finished')
			return
		self.step = self.step+1
		self.pbar.setValue(self.step)
	def doAction(self):
		if self.timer.isActive():
			self.timer.stop()
			self.btn.setText('Start')
		else:
			self.timer.start(100,self)
			self.btn.setText('Stop')
	def initAction(self):
		self.step = 0
		self.pbar.setValue(0)
		self.btn.setText('Start')
			
    
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())