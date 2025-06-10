## 📑 Setup Instructions


### 1. Unzip the project files

```
unzip laravel-crm.zip
cd laravel-crm
```

### 2. Install PHP dependencies using Composer

```
composer install
```

### 3. Install frontend dependencies using NPM

```
npm install
```

### 4. Generate .env configuration file

```
cp .env.example .env
```

### 5. Create a MySQL database
- Create a new database in your MySQL server for the project.

### 6. Update .env file
```
DB_CONNECTION=mysql
DB_DATABASE=crm_practical
DB_USERNAME=root
DB_PASSWORD=
```
- Then, generate the application key:
```
php artisan key:generate
```

### 7. Run database migrations and seeders
```
php artisan migrate --seed
```
### 8. Link storage for file uploading
```
php artisan storage:link
```

### 9. Build frontend assets
```
npm run build
```

### 10. Start the Laravel development server
```
php artisan serve
```

### 11. Update Laravel server url into the .env (used in spatie image)
```
APP_URL=http://127.0.0.1:8000
```

## Default Test Login Credentials

Use the following credentials to log into the system:

Email: ``test@example.com``

Password: ``password``