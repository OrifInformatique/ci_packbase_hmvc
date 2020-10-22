import mariadb
import sys

# Connection to db
try:
    conn = mariadb.connect(
        user="root",
        password="",
        host="127.0.0.1",
        port=3306,
        database="ci_packbase_hmvc"
    )
except mariadb.Error as e:
    print(f"Error connecting to MariaDB Platform: {e}")
    sys.exit(1)


# instance cursor
cur = conn.cursor()

# datas
fk_user_type = 2
password = "$2y$10$jy2gpqZtwuU5WuCcuHBg.eK7.U74/iHKclkB3e0VMfDziJTOGfvv."
email = "dummy@dummy.com"
archive = 0

count = 0

# SQL Query
try:
    while count < 250:
        username = f"dummy{count}"
        cur.execute("INSERT INTO ci_packbase_hmvc.user (fk_user_type, username,password,email,archive) VALUES (?,?,?,?,?)",
                    (fk_user_type,username,password,email,archive))
        count += 1
except mariadb.Error as e:
    print(f"SQL error : {e}")


# commit Query
conn.commit() 


# kill cursor instance 
conn.close()