# Pam's Market

Pam's Market needs to return its customers' money. A Third-party service called Slightly-big Flip is used for its customers' disbursement.

## Initialization

Requires PHP 7.2.24 and MySQL 14.14 packages.

```bash
sudo apt install php
sudo apt install mysql-server
```

Set environment variables for Slightly-big Flip secret key and database credentials.

```bash
export SECRET_KEY=<secret_key>
export DB_USERNAME=<db_username>
export DB_PASSWORD=<db_password>
export DB_NAME=<db_name>
```

## Usage

**Migrate database**. Create a database and disbursement table if not exists.
```bash
php manage.php migrate
```
**Send disbursement**. Send disbursement to Slightly-big Flip and store the received information 
```bash
php manage.php send_disbursement <bank_code> <account_number> <amount> <remark>
```
**Update disbursement**. Update disbursement information based on Slightly-big Flip received response.
```bash
php manage.php update_disbursement <disbursement_id>
```
