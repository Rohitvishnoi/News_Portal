#import libraries
from urllib.request import Request, urlopen
from bs4 import BeautifulSoup
import requests
import re
import sys
import parse_class as ps
import time
import argparse


def key_word_process(keyword, file_mapping):
	"""This is the process keyword function.
	It takes as input keyword which is the
	characters given by the user to search and
	file_mapping which is the name of the file
	which contains mapping from character into special
	characters. This function substitutes those characters
	with special characters with the same mapping.
	Parameters
	----------
	keyword: String
	file_mapping: String

	Returns
	-------
	n_keyword: String
	"""
	words_mapping= {}
	with open(file_mapping) as f:
		for line in f:
			key = line[0]
			val = line.split()[-1]
			words_mapping[key] = val
	
	n_keyword = ""
	for i in range(0,len(keyword)):
        
		if keyword[i] in words_mapping.keys():
			n_keyword = n_keyword + words_mapping[keyword[i]]
		else:
			n_keyword = n_keyword + keyword[i]
	return n_keyword

def main():
	"""This is the main
	Get keyword to search,character mapping file name, category name
	result bound, author through command line.
	Defaults for keyword, character mapping file, category name,
	result bound and author names are "Trump", "map.txt", "Misc",
	"10", "root" respectively.
	Main fetches news URLs, Images, Title and Description
	from goole news, according to the keyword searched.
	
	Parameters
	----------
	keyword (optional): Command line
	
	character mapping file (optional): Command line
		This file specifies the special character mapping in the URL.
	
	category (optional): Commmand line.
		This the category to insert the news.
	
	fetch_result_number : Command line.
		This is the maximum number of results.
	
	author: Command line.
		This specifies the author name.

	Returns
	-------
	None
	"""
	
	
	#create command line arguments
	parser = argparse.ArgumentParser()
	parser.add_argument("-k","--keyword",metavar="", required=False, help="Keyword to search")
	parser.add_argument("-f","--special_character_mapping",metavar="", required=False, help="File name of special characters")
	parser.add_argument("-c","--category",metavar="", required=False, help="News category")
	parser.add_argument("-n","--fetch_result",metavar="", required=False, help="Maximum number of results")
	parser.add_argument("-a","--author",metavar="", required=False, help="Author")
	parser.add_argument("-p","--pretty_print",action='store_true',required=False, help="Dump the pretty results on stdout")
	parser.add_argument("-r","--pretty_print_brackets",metavar="", required=False, help="File name to dump the nested bracket structure")
	parser.add_argument("-d","--pretty_print_data",metavar="", required=False, help="File name to dump the pretty structure with data")
	parser.add_argument("-w","--pretty_print_html",metavar="", required=False, help="File name to dump the html generated file")
	
	
	args = parser.parse_args()

	#default character mapping file
	file_mapping = "map.txt"
	if args.special_character_mapping:
		file_mapping = args.special_character_mapping
    
	#default category is Misc
	category = "Misc"
	if args.category:
		category = args.category
	
	#default number of fetches is 10
	num = 10
	if args.fetch_result:
		num = int(args.fetch_result)
	
	#default author is root
	author = "root"
	if args.author:
		author = (args.author)

	#default keyword is Trump
	key_word = "Trump"
	if args.keyword:
		key_word = args.keyword

	#parse keyword according to character mappings
	key_word = key_word_process(key_word, file_mapping)

	#the root link
	root = "https://www.google.com/"

	#the search link with keyword
	link = "https://news.google.com/search?q="+key_word+"&hl=en-IN&gl=IN&ceid=IN:en"

	#request for the link
	#req = Request(link, headers={'User-Agent': 'Mozilla/5.0'})

	#add a print statement to have more interaction
	print("opening url...", link)
	
	#read the webpage
	source = requests.get(link).text

	#use beautiful soup
	soup = BeautifulSoup(source, 'lxml')

	#add a print statement to have more interaction
	print("opened url...complete")

	#print formatted output
	#print(soup.prettify())
	
	#create a temporary object of the parse class
	article_obj = ps.param_matcher(str(soup.find_all('script')[17].text))

	
	#print pretty the bracket structure
	if args.pretty_print_brackets:
		#print only the nested [] in the tmp file name
		article_obj.rem_brackets("tmp_"+str(args.pretty_print_brackets)+"_"+key_word+"_rm_brackets_data.txt")
	
	#print the textual data
	if args.pretty_print_data:
		#print all the textual data prettily in the file
		article_obj.pretty_print("tmp_"+str(args.pretty_print_data)+key_word+"_rm_pretty.txt")



	#populate data in tree data structure
	article_obj.populate_data()

	#print all data in html format in file
	if args.pretty_print_html:
		#print all the data in html format
		article_obj.html_print(args.pretty_print_html)	
	
	#print data simply on terminal/std out
	if args.pretty_print:
		article_obj.parser_to_print()
	
	#populate the SQL tables with data
	if args.fetch_result:
		article_obj.populate_sql(num, category,author)
	
if __name__ == "__main__":
	main()
