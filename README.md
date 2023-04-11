## Start
Basic Requirement

| Laravel version |	PHP     |
| ------------ | ------------ |
| ^10  | ^8.1                     |



Clone the repository
```
git clone https://github.com/zulkawsar/next-form.git
```
Go to the folder
```
cd next-form
```
Update the Composer ( Before run the composer update command please enable the ```extension=gd ```  )
```
composer update
npm install
```

Then you need to copy the .env.example to .env
```
cp .env.example .env
php artisan key:generate
```
**The set the database information **
- DB_DATABASE=laravel
- DB_USERNAME=root
- DB_PASSWORD=

Then run the seeder
```
php artisan migrate:fresh --seed
```
Its generate some fake data.

After finishing run the application
```
php artisan serve
```
**open an another terminal and browse the folder and run the below command **
```
npm run dev
```
now application has been running in 
http://localhost:8000/

**Login in to dashboard or register as new user**
login credientials
- email: kawsar@gmail.com
- password: password

After successfully login, we can find there are four menu
1. **Dashboard**
2. **Generate form** 
	Here you can add / remove user(consiter as institute ) wise custom field for students 
3. **Student Form** 
	- After completing the **Generate form** , Here you can find all the custom field and all common field
	- Fill up the form accoding to the instruction (Fronted and Backend both validate given)
	- Custom field validation also given

4. **Student List **
	- We can find all the student list 
	- pagination given

##### We can try same procedure for another new register user
If you have any query please email: zulkawsar@gmail.com
