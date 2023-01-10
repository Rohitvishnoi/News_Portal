#import libraries
from urllib.request import Request, urlopen
from bs4 import BeautifulSoup
import requests
import re
import sys
import parse_class as ps
import time
import os
import imghdr
import sql_fill as sql_f

class param_matcher:
	"""
	This class is the node of a tree.
	Each node stores textual content and
	pointers to it's children.
	"""

	def __init__(self, list_str):
		"""Constructor of the class.
			Populates the articles in the tree
			Parameters
			----------
				list_str: This is the string made of
				nested brackets of [] with textual
				entries in the nested brackets
			Returns
			-------
			None
		"""
		#assign member variables to values
		self.curr = list_str
		
		#get the children indices
		self.child_list = self.children(list_str)
		
		#create a list of children pointers
		self.children_l = []
		
		#make children nodes and populate children
		for i in self.child_list:
			new_child=param_matcher(list_str[i[0]:i[1]+1])
			self.children_l.append(new_child)



	def populate_data(self):
		"""This function parses the tree rooted at
			current node and stores all the articles
			which are the children of this node in
			a list
		
			Parameters
			----------
			None

			Returns
			-------
			None
		"""
		self.articles = []

		article_list = self.children_l[0].children_l[0].children_l[0].children_l[1].children_l
		#iterate through the appropriate children
		for i in range(0,len(article_list)):
			
			#index of the initial child in the sibling
			sibling = 3

			if i>2:##better	to avoid coverage news with different parse
				
				str_title = ""
				str_title_desc = ""
				str_url = ""
				str_img_url = ""

				##iterate through sibling until title is not finished with "
				while article_list[i].children_l[0].curr.split(",")[sibling][-1]!='"':
					
					#store the siblings
					str_title = str_title + article_list[i].children_l[0].curr.split(",")[sibling]
					
					sibling = sibling+1
				
				#store the final sibling
				str_title = str_title + article_list[i].children_l[0].curr.split(",")[sibling]
				str_title = str_title[1:-1]
				# print(str_title, end = "\t")


				##go to the next sibling
				sibling = sibling+1
				
				##iterate through sibling until title description is not finished with "
				while article_list[i].children_l[0].curr.split(",")[sibling][-1]!='"':
					
					str_title_desc = str_title_desc + article_list[i].children_l[0].curr.split(",")[sibling]
					
					sibling = sibling+1


				str_title_desc = str_title_desc + article_list[i].children_l[0].curr.split(",")[sibling]
				str_title_desc = str_title_desc[1:-1]
				# print(str_title_desc,end="\t")
				
				#go to the next sibling
				sibling = sibling+3
				
				#store the url of the news article
				str_url = str_url + article_list[i].children_l[0].curr.split(",")[sibling]
				str_url = str_url[1:-1]
				# print(str_url, end = "\t")

				#go to the next sibling
				sibling = sibling+2

				#store the url of the image of the news article
				str_img_url = str_img_url + article_list[i].children_l[0].curr.split(",")[sibling][3:]
				str_img_url = str_img_url[:-1]
				# print(str_img_url, end="\t")


				self.articles.append([str_title, str_title_desc, str_url, str_img_url])
				#pretty print
				# print()
				# print()
				# print()



	def html_print(self,file_name=""):
		"""This function prints a html table
			of all the fetched articles. The
			columns of the tables are Title,
			Title Description, URL of the article,
			Image URL respectively.
		
			Parameters
			----------
			file_name (Optional): String
			If file name is passed, then instead of stdout,
			print content in the file

			Returns
			-------
			None
		"""

		#print on std out
		if file_name =="":
			
			#print table block
			print("<table>")

			#start the header row
			print("\t<tr>")

			#print table headers as specified in doc string above
			print("\t\t<th>Title</th>")
			print("\t\t<th>Title Description</th>")
			print("\t\t<th>URL</th>")
			print("\t\t<th>Image URL</th>")
			print("\t</tr>")
			
			#iterate through each article to print the article
			for iter_article in self.articles:

				#print article details in a row
				print("\t<tr>")

				#print article Title
				print("\t\t<td>",iter_article[0],"</td>")
				
				#print article Title Description
				print("\t\t<td>",iter_article[1],"</td>")
				
				#print article URL
				print("\t\t<td>",iter_article[2],"</td>")
				
				#print article Image URL
				print("\t\t<td>",iter_article[3],"</td>")

				#end the row
				print("\t</tr>")
			
			#end the table
			print("</table>")
		
		#print in the file
		else:
			with open(file_name,"w") as file_write:
				#print table block

				file_write.write("<html>")
				file_write.write("<body>")
				
				file_write.write("<table border>")

				#start the header row
				file_write.write("\t<tr>")

				#print table headers as specified in doc string above
				file_write.write("\t\t<th>Title</th>")
				file_write.write("\t\t<th>Title Description</th>")
				file_write.write("\t\t<th>URL</th>")
				file_write.write("\t\t<th>Image URL</th>")
				file_write.write("\t</tr>")
				
				#iterate through each article to print the article
				for iter_article in self.articles:

					#print article details in a row
					file_write.write("\t<tr>")

					#print article Title
					file_write.write("\t\t<td>"+str(iter_article[0])+"</td>")
					
					#print article Title Description
					file_write.write("\t\t<td>"+str(iter_article[1])+"</td>")
					
					#print article URL
					file_write.write("\t\t<td>"+str(iter_article[2])+"</td>")
					
					#print article Image URL
					file_write.write("\t\t<td>"+str(iter_article[3])+"</td>")

					#end the row
					file_write.write("\t</tr>")
				
				#end the table
				file_write.write("</table>")
				file_write.write("</body>")
				file_write.write("</body>")


	def populate_sql(self, num, category, author,print_html=0):
		"""This function populates the admin side SQL tables and
			also prints the new added articles if print_html argument
			passed is 1
			
			Parameters
			----------
			num: Integer
				This is the upper bound of number of articles to add in SQL tables.
				The articles added are minimum of articles fetched and num.
			
			category: String
				This is category of the fetched articles in the SQL table.
			
			author: String
				This is the name of the author who is adding the news articles.
			
			print_html (Optional): Bool
				This value specifies whether to print the html table of new added articles.
				If this argument is 1, then print the table, otherwise do not print the table.

			Returns
			-------
			None
		"""
		#get all articles

		all_articles = self.articles
		
		#assign id
		id = author

		#print the initial heading of the table
		if print_html==1:
			print("<table>")
			print("\t<tr>")
			print("\t\t<th>Title</th>")
			print("\t\t<th>Title Description</th>")
			print("\t\t<th>URL</th>")
			print("\t\t<th>Image URL</th>")
			print("\t\t<th>Actual_image</th>")
			print("\t</tr>")
		
		#iterate over articles to add in the SQL table
		iter_article=0

		#iterate until upper bound is not reached
		while iter_article < min(num,len(all_articles)):
			
			#get the subject, or the title of the article
			subject = all_articles[iter_article][0]

			#get the title description of the article
			description = str(all_articles[iter_article][1])+"<a href= \""+str(all_articles[iter_article][2])+"\">Read More</a>"
			
			#try to fetch the image of the article. If no image is provided
			#in google news, then skip the article
			try:
				response = requests.get(all_articles[iter_article][3])
			except:
				#skip the article if no image link
				iter_article = iter_article + 1
				num = num+1
				continue
			
			#create a soup for the image
			soup1 = BeautifulSoup(response.text,'html.parser')
			
			#name the image according to system time for uniqueness
			image = str(time.time())
			
			#store the image in assets/upload/article/
			try:
				file = open("assets/upload/article/"+image, "wb")
				file.write(response.content)
				file.close()

				#get the extension of the image
				img_type = imghdr.what("assets/upload/article/"+image)
				
				#change the name of the image to include it's extension
				img_final_name = "assets/upload/article/"+image+"."+str(img_type)

				#rename the image with appropriate extension
				os.rename("assets/upload/article/"+image,img_final_name)
				
			except:
				#if there is a problem in uploading the image then raise an exception
				print("IMAGE UPLOADING PROBLEM")
				raise Exception("Image upload error.")
			
			#print the uploaded article in the html file
			if print_html==1:
				
				#print the current row
				print("\t<tr>")
				
				#print the title of article
				print("\t\t<td>",subject,"</td>")

				#print the Description of the article
				print("\t\t<td>",all_articles[iter_article][1],"</td>")
				
				#print the description with read more or URL
				print("\t\t<td>",description,"</td>")

				#print the URL of the image
				print("\t\t<td>",all_articles[iter_article][3],"</td>")

				#print the image
				print("\t\t<td><img src=",img_final_name, " style=\"width:500px;height:600px;\">","</td>",sep="")
				
				#end the current row
				print("\t</tr>")
			
			# subject = all_articles[iter_article][0]
			# description = str(all_articles[iter_article][1])+"<a href= \""+str(all_articles[iter_article][2])+"\">Read More</a>"
			# image =img_loc[i].split("/")[-1]
			# print("Trying to upload on database", img_final_name)
			
			#try to update the database, with contents of article, title, title description, image, and
			# send email
			try:
				#connect to database object
				swl_obj = sql_f.sql_fill("root","Animesh@98","cs699proj")
				#send an email to subscribed users
				swl_obj.send_email(subject,description)
				#store the article in the database
				swl_obj.store_article(img_final_name.split("/")[-1], subject, category, description, id)
			except:
				#print Unable to upload in database if results in exception
				print("UNABLE TO UPLOAD IN DATABASE")
			
			#go to the next article
			iter_article = iter_article+1
		
		#print the end of the html table
		if print_html==1:
			print("</table>")
			print("</html>")		
		
			
			# #establishing connection to database to fetch the emails to send updates
			# connection = pymysql.connect(host="localhost",user="root",passwd="Animesh@98",database="cs699proj" )
			# cursor = connection.cursor()
			# cursor.execute("SELECT * FROM emails")
			# to = ["cs699project2021@gmail.com"]

			# for row in cursor.fetchall():
			# 	to.append(row[1])

			# #setting variables for sending mail updates to users
			# gmail_user = 'cs699project2021@gmail.com'
			# gmail_pwd = 'Cs699@project2021'
			# smtpserver = smtplib.SMTP("smtp.gmail.com", 587)
			# smtpserver.ehlo()
			# smtpserver.starttls()
			# smtpserver.ehlo
			# smtpserver.login(gmail_user, gmail_pwd)

			# #setting up the mail content
			# msg = '<h1>'+subject+'</h1>' + '<br>' + description

			# #Setup the MIME and sending the mail
			# message = MIMEMultipart()
			# message['From'] = gmail_user
			# message['To'] = ",".join(to)
			# message['Subject'] = 'New news article added to XYZ news portal. Have a look.'
			# message.attach(MIMEText(msg, 'html'))
			# text = message.as_string()
			# smtpserver.sendmail(gmail_user, to, text)
			# smtpserver.close()

			#storing news article in database
			# sql = """INSERT INTO article (`article_title_img`,`article_title`,`article_category`,`article_desc`,`admin_id`) VALUES (%s, %s, %s,%s, %s)"""
			# cursor.execute(sql, (image, subject, category, str(description), str(id)))
			# connection.commit()
			# connection.close()


		

		
	def pretty_print(self, file_name):
		"""Print pretty the textual contents as well
			as the brackets in nested order.
			
			Parameters
			----------
				file_name: String
				the file name to write the pretty values
			
			Returns
			-------
				string: the string which is the pretty print in 
				the file
		"""
		#to_construct is the string to make to write to file
		#or to print
		to_construct = ""
		
		#keep count of number of tab characters
		tab_chars = 0
		is_prev = 0

		#keep parity of opened quotes
		quotes_open = 0

		#iterate throught the textual data
		for i in range(0,len(self.curr)):
			
			#if quotes are not open only then consider the brackets
			if quotes_open==0:

				#if current character is bracket open, then start a newline
				#and increase the tab characters
				if self.curr[i] == "[":
					to_construct = to_construct + "\n"
					for tabs in range(0,(tab_chars)):
						to_construct = to_construct + " "
					to_construct = to_construct + self.curr[i]
					tab_chars = tab_chars + 1
					is_prev = 0

				#if current character is bracket close, then finish the recent
				#bracket open and and dencreste the tab characters
				elif self.curr[i] == "]":
					if is_prev==0:
						to_construct = to_construct + self.curr[i]
						# to_construct = to_construct + "\n"
						tab_chars = tab_chars - 1
						is_prev=1
					else:
						tab_chars = tab_chars - 1
						to_construct = to_construct + "\n"
						for tabs in range(0,(tab_chars)):
							to_construct = to_construct + " "
						to_construct = to_construct + self.curr[i]
				
				#other wise this is a textual data
				else:
					to_construct = to_construct + self.curr[i]

			#if quotes are open then consider this as textual data
			else:
				to_construct = to_construct + self.curr[i]
			

			#if the current character is a quote, then appropriately
			#open or close quotes
			if 	self.curr[i] == '"':
				count_back_slash = 0
				index = i-1
				while(index!=0 and self.curr[index]=="\\"):
					
					count_back_slash = count_back_slash+1
					index = index - 1
				# if self.curr[i-1] !='\\':
				if count_back_slash%2==0:
					quotes_open = 1-quotes_open

		#write the textual data in the file
		with open(file_name, "w") as f:
			f.write(to_construct)
		

		return to_construct


	def rem_brackets(self, file_name):
		"""Print pretty only the
			brackets in nested order.
			
			Parameters
			----------
				file_name: String
				the file name to write the pretty values
			
			Returns
			-------
				string: the string which is the pretty print in 
				the file
		"""
		
		#to_construct is the string to make to write to file
		#or to print
		to_construct = ""
		
		#keep count of number of tab characters
		tab_chars = 0
		is_prev = 0

		#keep parity of opened quotes
		quotes_open = 0

		#iterate throught the textual data
		for i in range(0,len(self.curr)):
			
			#if quotes are not open only then consider the brackets
			if quotes_open==0:
				
				#if current character is bracket open, then start a newline
				#and increase the tab characters
				if self.curr[i] == "[":
					to_construct = to_construct + "\n"
					for tabs in range(0,(tab_chars)):
						to_construct = to_construct + " "
					to_construct = to_construct + self.curr[i]
					tab_chars = tab_chars + 1
					is_prev = 0
				
				#if current character is bracket close, then finish the recent
				#bracket open and and dencrease the tab characters
				elif self.curr[i] == "]":
					if is_prev==0:
						to_construct = to_construct + self.curr[i]
						# to_construct = to_construct + "\n"
						tab_chars = tab_chars - 1
						is_prev=1
					else:
						tab_chars = tab_chars - 1
						to_construct = to_construct + "\n"
						for tabs in range(0,(tab_chars)):
							to_construct = to_construct + " "
						to_construct = to_construct + self.curr[i]
			
			#if the current character is a quote, then appropriately
			#open or close quotes
			if 	self.curr[i] == '"':
				count_back_slash = 0
				index = i-1
				while(index!=0 and self.curr[index]=="\\"):
					
					count_back_slash = count_back_slash+1
					index = index - 1
				# if self.curr[i-1] !='\\':
				if count_back_slash%2==0:
					quotes_open = 1-quotes_open

		#write the brackets only in the file			
		with open(file_name, "w") as f:
			f.write(to_construct)
		
		return to_construct
		
	def children(self, str_curr):
		"""Populate the children of current
			node
			
			Parameters
			----------
				str_curr: String
				the current string
			
			Returns
			-------
				string: the list of locations
				in the str_curr to have nested
				blocks
		"""

		#initially this node has no children
		children = 0
		
		#keep the total count on number of nested brackets
		count_bracket = 0
		
		#keep a list of all locations of nested brackets,
		#opening and closing brackets
		bracket_loc_indices = []
		opening_closing_brackets = []
		children_location = []

		#keep the parity of open quotes
		quotes_open = 0
		
		#iterate through the current string to capture nested brackets
		for i in range(0,len(str_curr)):
			#print(i, str_curr[i], len(str_curr), count_bracket)

			#if quotes are not open only then consider the brackets
			if quotes_open==0:

				#if current character is bracket open, then start a child
				if str_curr[i]=="[":
					count_bracket = count_bracket+1
					opening_closing_brackets.append(i+1)
				
				#if current character is bracket close, then finish 
				# collecting the data of recent child
				
				elif str_curr[i] == "]":
					#increase bracket count and make children appropriately
					count_bracket = count_bracket-1
					opening_last_loc = opening_closing_brackets.pop()
					closing_last_loc = i-1
					bracket_loc_indices.append((opening_last_loc,closing_last_loc))

					#Raise EXCEPTION in case of parsing error
					if count_bracket<0:
						raise Exception("Count Bracket Parsing Error")
					
					#Nested bracket structure completed
					if count_bracket == 0:
						children = children + 1
						children_location.append((opening_last_loc,closing_last_loc))
			
			#if the current character is a quote, then appropriately
			#open or close quotes
			#if count_bracket<0:
			if 	self.curr[i] == '"':
				count_back_slash = 0
				index = i-1
				while(index!=0 and self.curr[index]=="\\"):
					count_back_slash = count_back_slash+1
					index = index - 1
				# if self.curr[i-1] !='\\':
				if count_back_slash%2==0:
					quotes_open = 1-quotes_open
		
		return children_location



	def parser_to_print(self):
		"""This function prints the Title, Title Description, URL, Image Url
		separated by tab of news articles fetched.
		
		Parameters
		----------
		None
		
		Returns
		-------
		None
			
		"""

		#iterate through the appropriate children
		for i in range(0,len(self.children_l[0].children_l[0].children_l[0].children_l[1].children_l)):
			
			#index of the initial child in the sibling
			sibling = 3

			if i>2:##better	to avoid coverage news with different parse
				
				##iterate through sibling until title is not finished with "
				while self.children_l[0].children_l[0].children_l[0].children_l[1].children_l[i].children_l[0].curr.split(",")[sibling][-1]!='"':
					
					#print the siblings
					print(self.children_l[0].children_l[0].children_l[0].children_l[1].children_l[i].children_l[0].curr.split(",")[sibling], end="")
					sibling = sibling+1
				
				#print the final sibling
				print(self.children_l[0].children_l[0].children_l[0].children_l[1].children_l[i].children_l[0].curr.split(",")[sibling],end="\t")
				
				##go to the next sibling
				sibling = sibling+1
				
				##iterate through sibling until title description is not finished with "
				while self.children_l[0].children_l[0].children_l[0].children_l[1].children_l[i].children_l[0].curr.split(",")[sibling][-1]!='"':
					print(self.children_l[0].children_l[0].children_l[0].children_l[1].children_l[i].children_l[0].curr.split(",")[sibling], end="")
					sibling = sibling+1
				print(self.children_l[0].children_l[0].children_l[0].children_l[1].children_l[i].children_l[0].curr.split(",")[sibling],end="\t")
				
				#go to the next sibling
				sibling = sibling+3
				
				#print the url of the news article
				print(self.children_l[0].children_l[0].children_l[0].children_l[1].children_l[i].children_l[0].curr.split(",")[sibling],end="\t")
				
				#go to the next sibling
				sibling = sibling+2

				#print the url of the image of the news article
				print(self.children_l[0].children_l[0].children_l[0].children_l[1].children_l[i].children_l[0].curr.split(",")[sibling][3:-1],end="\t")
				
				#pretty print
				print()
				print()
				print()
