
##  Design Database
![Diagram Class](https://github.com/fajarghifar/inventory-management-system/assets/71541409/0c7d4163-96f5-4724-8741-4615e52ecf98)

## 🚀 How to Use

1. Clone Repository

```bash
git clone https://github.com/fajarghifar/inventory-management-system
```

2. Go into the repository 

```bash
cd inventory-management-system
```

3. Install Packages 

```bash
composer install
```


4. Copy `.env` file 

```bash

cp .env.example .env

```

5. Generate app key 

```bash
php artisan key:generate
```

6. Setting up your database credentials in your `.env` file.
7. Seed Database: 

```bash

php artisan migrate:fresh --seed

```
8. Create Storage Link

```bash
php artisan storage:link
```

9. Install NPM dependencies 

```bash

npm install && npm run dev

```
10. Run 

```bash

php artisan serve

```
11. Try login with email: 

```bash

admin@admin.com

```
and password: 

```bash

password

```
