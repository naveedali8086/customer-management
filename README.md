## Introduction

This package provides a minimal customer management / CRUD scaffolding, in which a `Customer` entity: <br>- belongsTo 
an optional `CustomerGroup` entity <br>- morphMany `Contact` entity as well as <br>- optional morphMany `Address` entity.

It publishes controllers, models, formRequests, resources, migrations, factories, test cases for CustomerGroup, Customer, Contact and optional Address resources to your application that can be easily customized based on your own application's needs.

Also, if you have any other model/entity in your app that is contactable or addressable, you do not need to redefine
the contact or address related fields in that entity, controller, formRequests and so on. All you have to do is just add 
a new case in `App\Enums\ContactBelongsTo` as well as in `getContactParentModelClass()` method of this enum that represent 
your model/entity (once you look at how Customer Entity is defined in this enum, you would have clear understanding about 
how to add new entities) , and you are good to go. And same goes for the address entity. 

### Entities

#### CustomerGroup
| Attribute    | Type      | Required                     | Description                                                                                                                    |
|--------------|-----------|------------------------------|--------------------------------------------------------------------------------------------------------------------------------|
| id           | bigInt    | No <br>(handled by Laravel)  |                                                                                                                                |
| name         | string    | Yes                          | Using this field customers can be categorized. By default, this field is unique. However, It can be customized as per the need |
| description  | string    | No                           | Additional info about the customerGroup                                                                                        |
| created_at   | timestamp | No <br>(handled by Laravel)  |                                                                                                                                |
| updated_at   | timestamp | No <br>(handled by Laravel)  |                                                                                                                                |
| deleted_at   | timestamp | No <br>(handled by Laravel)  |                                                                                                                                |

#### Customer
| Attribute           | Type      | Required                     | Description                                                        |
|---------------------|-----------|------------------------------|--------------------------------------------------------------------|
| id                  | bigInt    | No <br>(handled by Laravel)  |                                                                    |
| first_name          | string    | Yes                          |                                                                    |
| middle_name         | string    | No                           |                                                                    |
| last_name           | string    | Yes                          |                                                                    |
| customer_code       | string    | No                           | Not required but if present, must be unique                        |
| enable_notification | boolean   | No                           | whether the emails/sms should be sent <br/>i.e. promotional emails |
| date_of_birth       | date      | No                           |                                                                    |
| gender              | string    | Yes                          | Possible values are "M", "F" or "Other"                            |
| gender_other        | string    | Yes/No                       | required_if gender attribute =Other                                |
| note                | string    | No                           | An optional note/message that customer mentioned                   |
| created_at          | timestamp | No <br>(handled by Laravel)  |                                                                    |
| updated_at          | timestamp | No <br>(handled by Laravel)  |                                                                    |
| deleted_at          | timestamp | No <br>(handled by Laravel)  |                                                                    |

#### Contact
| Attribute        | Type      | Required                    | Description                                                                                                                                                                                                                                                                                                                                                                                               |
|------------------|-----------|-----------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| id               | bigInt    | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                                                                                                                                                           |
| channel          | string    | Yes                         | the contact channel that will be used to contact. <br/> Email, Mobile, Other (twitter, instagram,...)                                                                                                                                                                                                                                                                                                     |
| channel_other    | string    | Yes                         | required_if channel attribute = Other                                                                                                                                                                                                                                                                                                                                                                     |
| channel_value    | string    | Yes                         | the value of the channel i.e. if channel attribute = Email <br/> then this attribute would be an email and if channel attribute is Mobile then this attribute would be a mobile number and if channel attribute = Other then channel_value attribute would depend on channel_other attribute's value. For example if channel=Other, channel_other=twitter and then channel_value=twitter handle and so on |
| contactable_id   | bigInt    | Yes                         | The ID of the parent model this contact belongs to. i.e. the parent model may be a Customer, an Employee and so on                                                                                                                                                                                                                                                                                        |
| contactable_type | string    | Yes                         | The string identifier of the parent model this contact belongs to. i.e. It may a App\Models\Customer, App\Models\Employee and so on <br/> (note: as  per laravel docs, this string identifier is customizable and for this reason a belongs_to attribute was added below)                                                                                                                                 |
| belongs_to       | string    | Yes                         | The string identifier of the parent model this contact belongs to. This string identifier would be unique_key per model                                                                                                                                                                                                                                                                                   |
| created_at       | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                                                                                                                                                           |
| updated_at       | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                                                                                                                                                           |
| deleted_at       | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                                                                                                                                                           |

#### Address
| Attribute               | Type      | Required                    | Description                                                                                                                                                                                                                                                               |
|-------------------------|-----------|-----------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| id                      | bigInt    | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                           |
| line_1                  | string    | Yes                         |                                                                                                                                                                                                                                                                           |
| line_2                  | string    | No                          |                                                                                                                                                                                                                                                                           |
| postal_code             | string    | No                          |                                                                                                                                                                                                                                                                           |
| country_id              | bigInt    | Yes                         |                                                                                                                                                                                                                                                                           |
| region_id               | bigInt    | Yes                         |                                                                                                                                                                                                                                                                           |
| city_id                 | bigInt    | Yes                         |                                                                                                                                                                                                                                                                           |
| landmark                | string    | No                          |                                                                                                                                                                                                                                                                           |
| lat                     | string    | No                          |                                                                                                                                                                                                                                                                           |
| long                    | string    | No                          |                                                                                                                                                                                                                                                                           |
| addressable_id          | bigInt    | Yes                         | The ID of the parent model this address belongs to. i.e. the parent model may a Customer, an Employee and so on                                                                                                                                                           |
| addressable_type        | string    | Yes                         | The string identifier of the parent model this address belongs to. i.e. It may a App\Models\Customer, App\Models\Employee and so on <br/> (note: as  per laravel docs, this string identifier is customizable and for this reason a belongs_to attribute was added below) |
| belongs_to              | string    | Yes                         | The string identifier of the parent model this address belongs to. This string identifier would be unique_key per model                                                                                                                                                   |
| applicable_for_shipping | boolean   | No                          |                                                                                                                                                                                                                                                                           |
| created_at              | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                           |
| updated_at              | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                           |
| deleted_at              | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                           |


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

Also, if address-management package was installed, please uncomment addresses() relationship method in defined in Customer model. 

<br>
Step 3:
<br>

```
php artisan migrate
```

<br>
Step 4:
<br>

Define customerGroup, customer, contact and (optional) address crud routes in `api.php` or `web.php` routes files as per your needs.
```
api.php:
--------
Route::apiResource('customer_groups', CustomerGroupController::class)
Route::apiResource('customers', CustomerController::class)
Route::apiResource('contacts', ContactController::class)
Route::apiResource('addresses', AddressController::class)

web.php:
--------
Route::resource('customer_groups', CustomerGroupController::class)
Route::resource('customers', CustomerController::class)
Route::resource('contacts', ContactController::class)
Route::resource('addresses', AddressController::class)

Note: you may be need to append except() or only() functions in above routes in web.php to only add routes that provide CRUD's backend functionality
```

### Run the tests
 
```
php artisan test tests/Feature/CustomerGroupControllerTest.php
php artisan test tests/Feature/CustomerControllerTest.php
php artisan test tests/Feature/ContactControllerTest.php
php artisan test tests/Feature/AddressControllerTest.php
```
P.S. Or run all the tests in one go using <br> `php artisan test tests/Feature/CustomerGroupControllerTest.php tests/Feature/CustomerControllerTest.php tests/Feature/ContactControllerTest.php tests/Feature/AddressControllerTest.php` <br>
and so.  

### Questions?
In case there is anything unclear feel free to reach out to me at naveedali8086@gmail.com

### Authors

[**Naveed Ali**](https://github.com/naveedali8086)

### License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details


