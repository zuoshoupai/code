#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QApplication,QWidget,QToolTip,QPushButton,QApplication)
from PyQt5.QtGui import QIcon,QFont

class Example(QWidget):
	def __init__(self):
		super().__init__()
		self.initUI()

	def initUI(self):

		QToolTip.setFont(QFont('SansSerif',10))
		self.setToolTip('This is interesting!')

		btn = QPushButton('Button',self)
		btn.setToolTip('This is <b>QpushButton</b> widget')
		btn.resize(btn.sizeHint())
		btn.move(50,50)

		self.setGeometry(300,300,300,220)
		self.setWindowTitle('Button')
		self.setWindowIcon(QIcon('web.png'))
		self.show()
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())