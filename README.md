## Introduction

This package provides a minimal customer management / CRUD scaffolding, in which a `Customer` entity: <br>- belongsTo 
an optional `CustomerGroup` entity <br>- morphMany `Contact` entity as well as <br>- optional morphMany `Address` entity.

It publishes controllers, models, formRequests, resources, migrations, factories, test cases for Customer, CustomerGroup, Contact and optional Address resources to your application that can be easily customized based on your own application's needs.


## How to install
Step 1:
```
composer require naveedali8086/customer-management
composer require naveedali8086/contact-management
```

(Optional: include following package if you want to have address(es) for a customer entity)
```
composer require naveedali8086/address-management 
```
<br>
Step 2:
<br>
Now publish the scaffolding provided by all these packages via:

```
php artisan customer-crud:install
php artisan contact-crud:install
php artisan address-crud:install (run this only if you have added "address-management" package
```

###### Note:
The `Contact` and `Address` entities have a `morphTo` relationship (`contactable` and `addressable` respectively) 
defined in them. It means that the `Contact` entity may belong to multiple parent entities. i.e. A contact may belong to
a Customer, Supplier, Doctor, Patient etc. If you added new parent for a `Contact` entity, it must be defined in as a 
new case of in `App\Enums\ContactBelongsTo` as well as in `getContactParentModelClass()` method of this enum. And same 
goes for the address entity.

<br>
Step 3:
<br>

```
php artisan migrate
```

<br>
Step 4:
<br>

Define customer, contact and (optional) address crud routes in `api.php` or `web.php` routes files as per your needs.

### Run the tests
 
```
php artisan test tests/Feature/CustomerGroupControllerTest.php
php artisan test tests/Feature/CustomerControllerTest.php
php artisan test tests/Feature/ContactControllerTest.php
php artisan test tests/Feature/AddressControllerTest.php
```
P.S. You can run all the tests in one go using <br> `php artisan test path/to/FirstTest.php path/to/SecondTest.php` <br>
and so.  

### Authors

[**Naveed Ali**](https://github.com/naveedali8086)

### License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details


