## Introduction

This package provides a minimal customer management / CRUD scaffolding, in which a `Customer` entity: <br>- belongsTo 
an optional `CustomerGroup` entity <br>- morphMany `Contact` entity as well as <br>- optional morphMany `Address` entity.

It publishes controllers, models, formRequests, resources, migrations, factories, test cases for Customer, CustomerGroup, Contact and optional Address resources to your application that can be easily customized based on your own application's needs.

### Entities

#### Customer
| Attribute           | Type      | Required                    | Description                                                        |
|---------------------|-----------|-----------------------------|--------------------------------------------------------------------|
| id                  | bigInt    | Yes (auto_increment)        |                                                                    |
| first_name          | string    | Yes                         |                                                                    |
| middle_name         | string    | No                          |                                                                    |
| last_name           | string    | Yes                         |                                                                    |
| customer_code       | string    | No                          | Not required but if present, must be unique                        |
| enable_notification | boolean   | No                          | whether the emails/sms should be sent <br/>i.e. promotional emails |
| date_of_birth       | date      | No                          |                                                                    |
| gender              | string    | Yes                         | Possible values are "M", "F" or "Other"                            |
| gender_other        | string    | Yes/No                      | required_if gender attribute =Other                                |
| note                | string    | No                          | An optional note/message that customer mentioned                     |
| created_at          | timestamp | No <br>(handled by Laravel) |                                                                    |
| updated_at          | timestamp | No <br>(handled by Laravel) |                                                                    |
| deleted_at          | timestamp | No <br>(handled by Laravel) |                                                                    |

<br>

#### Contact
| Attribute        | Type      | Required                    | Description                                                                                                                                                                                                                                                                                                                                                                         |
|------------------|-----------|-----------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| id               | bigInt    | Yes (auto_increment)        |                                                                                                                                                                                                                                                                                                                                                                                     |
| channel          | string    | Yes                         | the contact channel that will be used to contact. <br/> Email, Mobile, Other (twitter, instagram,...)                                                                                                                                                                                                                                                                               |
| channel_other    | string    | Yes                         | required_if channel attribute = Other                                                                                                                                                                                                                                                                                                                                               |
| channel_value    | string    | Yes                         | the value of the channel i.e. if channel attribute = Email <br/> then this attribute would be an email and if channel attribute is Mobile then this attribute would be a mobile number and if channel attribute = Other then channel_value attribute would depend on channel_other attribute's value. For example if channel=Other, channel_other=twitter and then channel_value=twitter handle and so on |
| contactable_id   | bigInt    | Yes                         | The ID of the parent model this contact belongs to. i.e. It may a customer model id, Employee model id and so on                                                                                                                                                                                                                                                                    |
| contactable_type | string    | Yes                         | The string identifier of the parent model this contact belongs to. i.e. It may a App\Models\Customer, App\Models\Employee and so on <br/> (note: as  per laravel docs, this string identifier is customizable and for this reason a belongs_to attribute was added below)                                                                                                           |
| belongs_to       | string    | Yes                         | The string identifier of the parent model this contact belongs to. This string identifier would be unique_key per model                                                                                                                                                                                                                                                                   |
| created_at       | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                                                                                                                                     |
| updated_at       | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                                                                                                                                     |
| deleted_at       | timestamp | No <br>(handled by Laravel) |                                                                                                                                                                                                                                                                                                                                                                                     |

<br>

#### Address
WIP - will be published sooner


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
P.S. Or run all the tests in one go using <br> `php artisan test tests/Feature/CustomerGroupControllerTest.php tests/Feature/CustomerControllerTest.php tests/Feature/ContactControllerTest.php tests/Feature/AddressControllerTest.php` <br>
and so.  

### Questions?
In case there is anything unclear feel free to reach out to me at naveedali8086@gmail.com

### Authors

[**Naveed Ali**](https://github.com/naveedali8086)

### License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details


