#! /usr/bin/python
# -*- coding:UTF-8 -*-
import sys
from PyQt5.QtWidgets import (QMainWindow,QTextEdit,QAction,QFileDialog,QApplication)
from PyQt5.QtGui import QIcon
class Example(QMainWindow):
	def __init__(self):
		super(Example,self).__init__()
		self.initUI()
	def initUI(self):
		
		self.textEdit = QTextEdit()
		self.setCentralWidget(self.textEdit)
		self.statusBar()
		openFile = QAction(QIcon('open.png'),'Open',self)
		openFile.setShortcut('Ctrl+o')
		openFile.setStatusTip('Open new File')
		openFile.triggered.connect(self.showDialog)

		menubar = self.menuBar()
		fileMenu = menubar.addMenu('&File')
		fileMenu.addAction(openFile)

		#self.move(300,150)
		self.setGeometry(300,300,290,150)
		self.setWindowTitle('Calculator')
		self.show()
	def showDialog(self):
		fname = QFileDialog.getOpenFileName(self,'Open file','/wamp/www/text')
		if fname[0]:
			f = open(fname[0],'r')
			with f:
				data = f.read()
				self.textEdit.setText(data)

			
    
if __name__== '__main__':
	app = QApplication(sys.argv)
	ex  = Example()
	sys.exit(app.exec_())