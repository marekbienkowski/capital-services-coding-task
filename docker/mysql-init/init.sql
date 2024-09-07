ALTER USER 'db_user'@'%' IDENTIFIED WITH mysql_native_password BY 'db_password';

GRANT ALL PRIVILEGES ON repayment_schedule.* TO 'db_user'@'%';

FLUSH PRIVILEGES;
