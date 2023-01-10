#inmporting libraries
import requests
from bs4 import BeautifulSoup
import sys
import json
import pymysql
import time
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import re


def main(url, category, id):
	"""From given URL, extract the webpage and
	store the title, description and image in SQL.

	Parameters
	----------
	url: String
		Url of the news article to extract
	
	category: String
		Category to store SQL data under

	id: String
		Id of the user fetching the webpages
	"""
	#defining the parser
	response = requests.get(url)
	soup = BeautifulSoup(response.text,'html.parser')

	#fetching the news article description using web scrapping
	description = ""
	for i in soup.find("div", id = re.compile("^content-body-")).find_all("p") :
	    description = description + i.text + "<br><br>"
	description = description + "<a href='" + url + "'>Read More...</a>"

	#fetching news article image and storing in local directory
	image = soup.picture.source["srcset"]
	response = requests.get(image)
	image = str(time.time()) + '_' + image.split('/')[-1]
	file = open("assets/upload/article/"+image, "wb")
	file.write(response.content)
	file.close()

	#establishing connection to database to fetch the emails to send updates
	connection = pymysql.connect(host="localhost",user="root",passwd="Animesh@98",database="cs699proj" )
	cursor = connection.cursor()
	cursor.execute("SELECT * FROM emails")
	to = ["cs699project2021@gmail.com"]

	for row in cursor.fetchall():
	    to.append(row[1])

	#setting variables for sending mail updates to users
	gmail_user = 'cs699project2021@gmail.com'
	gmail_pwd = 'Cs699@project2021'
	subject = str(soup.body.h1.text.replace("\n","").strip())
	smtpserver = smtplib.SMTP("smtp.gmail.com", 587)
	smtpserver.ehlo()
	smtpserver.starttls()
	smtpserver.ehlo
	smtpserver.login(gmail_user, gmail_pwd)

	#setting up the mail content
	msg = '<h1>'+subject+'</h1>' + '<br>' + description

	#Setup the MIME and sending the mail
	message = MIMEMultipart()
	message['From'] = gmail_user
	message['To'] = ",".join(to)
	message['Subject'] = 'New news article added to XYZ news portal. Have a look.'
	message.attach(MIMEText(msg, 'html'))
	text = message.as_string()
	smtpserver.sendmail(gmail_user, to, text)
	smtpserver.close()

	#storing news article in database
	sql = """INSERT INTO article (`article_title_img`,`article_title`,`article_category`,`article_desc`,`admin_id`) VALUES (%s, %s, %s,%s, %s)"""
	cursor.execute(sql, (image, subject, category, str(description), str(id)))
	connection.commit()
	connection.close()


if __name__=="__main__":
	
	# fetching arguements for news article from php
	url = sys.argv[1]
	category = sys.argv[2]
	id = sys.argv[3]
	
	#call the main function
	main(url, category, id)