#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QWidget,QCalendarWidget,QLabel,QApplication)
from PyQt5.QtCore import QDate
class Example(QWidget):
	def __init__(self):
		super(Example,self).__init__()
		self.initUI()
	def initUI(self):
		cal = QCalendarWidget(self)
		cal.setGridVisible(True)
		cal.move(20,20)
		cal.clicked[QDate].connect(self.showDate)
 		self.lb1 = QLabel(self)
		date = cal.selectedDate()
		self.lb1.setText(date.toString())
		#self.lb1.setText(date.toString())
		self.lb1.setGeometry(130,260,100,30)



		#self.move(300,150)
		self.setGeometry(300,300,350,300)
		self.setWindowTitle('CheckBox')
		self.show()
	def showDate(self,date):
		self.lb1.setText(date.toString())
	
			
    
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())