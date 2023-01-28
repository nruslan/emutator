# Emutator

## Helps to obfuscate email address from spam-bots

### Installation

    composer require nruslan/emutator
    
### Configuration for Laravel

Add the `eMail` alias into `Aliases` section in the `config/app.php` file by adding the following line:

```php
'eMail' => ruslan\Emutator\MailTo::class
```

### How to use

    {{ eMail::mutate('email@address.co') }}

### License
The Emutator is open-sourced package licensed under the [MIT license](https://opensource.org/licenses/MIT).
