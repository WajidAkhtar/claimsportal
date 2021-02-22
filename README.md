## Claims Form

### Server Requirement

- Check the server requiremts here. https://laravel.com/docs/8.x/deployment#server-requirements

### Configuration

- Clone Repository: git clone https://mahamadali121@bitbucket.org/mahamadali121/claims-form.git
- Install vendor packages via composer
    > composer install
- Link storage folder
    > php artisan storage:link
- Create .env file by copying .env.example and update the file as per requirement
    > cp .env.example .env
- Create database
- Change database settings in .env file
- Migrate database tables
    > php artisan migrate
- Seed database tables
    > php artisan db:seed
- Change app url and app name in .env file if neccessory, these url and name will be sent in emails.
- Update STMP settings in .env file for sending emails.