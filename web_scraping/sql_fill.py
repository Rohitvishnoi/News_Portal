import pymysql
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

class sql_fill:
	"""
		This class establishes SQL connection and 
		contains functions to upload and fetch data
		from SQL database
	"""
	def __init__(self,user_n, password,database_n):
		"""Create an object of the class.

			Parameters
			----------
				user_n: String
					This is the user name to connect to 
					the database
				
				password: String
					This is the password of the user
				
				database_n: String
					This is the name of the database

			Returns
			-------
			None
		"""

		#establish connection to database
		self.connection = pymysql.connect(host="localhost",user=user_n,passwd=password,database=database_n)
		self.cursor = self.connection.cursor()
		return
	
	def __del__(self):
		"""This is a destructor class
		which closes the connection to the
		database
			
			Parameters
			----------
			None
			
			Returns
			-------
			None
		"""
		self.connection.close()
		return
	
	def send_email(self,subject, description):
		"""This function sends email to all the
			subscribers
		
			Parameters
			----------
				subject: String
					This is subject of the article/email
				
				description: String
					This is the description of the article

			Returns
			-------
			None
		"""
		
		# to fetch the emails to send updates
		self.cursor.execute("SELECT * FROM emails")
		
		#also send email to company's main email ID
		to = ["cs699project2021@gmail.com"]

		#fetch all the emails
		for row in self.cursor.fetchall():
			to.append(row[1])

		#setting variables for sending mail updates to users
		gmail_user = 'cs699project2021@gmail.com'
		gmail_pwd = 'Cs699@project2021'
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
	
	def store_article(self, image, subject, category, description, id):
		"""This function inserts new content into database
			
			Parameters
			----------
				image: String
					This is the path to image in the article
				
				subject: String
					This is the title of the article

				category: String
					This is the category of the article
				
				description: String
					This is the desription of the article
				
				id: String
					This is the of the admin inserting the article

			Returns
			-------
			None
		"""
		#storing news article in database
		sql = """INSERT INTO article (`article_title_img`,`article_title`,`article_category`,`article_desc`,`admin_id`) VALUES (%s, %s, %s,%s, %s)"""
		self.cursor.execute(sql, (image, subject, category, str(description), str(id)))
		self.connection.commit()
