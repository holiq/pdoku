## PDOKu

PDOKu is a scaffolding for php rest api without using any framework.

### Installation

```sh
git clone git@github.com:holiq/pdoku.git
cd pdoku
mv config.example.php config.php
# you can change the default database configuration with your configuration
# run the server
php -S localhost:8000
```

### Usage

Here there is an example of using CRUD in the category table, you can copy and change it as needed.

```sh
/category.php => GET(Get all)
/category.php?id=6 => GET(Get one)
/category.php => POST(Store new) {"name": "Awesome"}
/category.php => PUT(Edit) {"id": 1, "name": "Awesome"}
/category.php => DELETE(Delete) {"id": 3}
```

If you want to use an example category, please create a table first with the following columns

```sh
categories
- id bigint not null auto increment
- name string(255) not null
- created_at datetime null
- updated_at datetime null
```
