# db_connection.py
import pymysql.cursors

def get_connection():
    return pymysql.connect(
        host='localhost',
        user='jroque',
        password='123456',
        database='restobar',
        cursorclass=pymysql.cursors.DictCursor
    )
