# orm

## V1.0.0

### Install via composer

Add orm to composer.json configuration file.
```
$ composer require komicho/orm
```

And update the composer
```
$ composer update
```

```php
require 'vendor/autoload.php';

use komicho\orm;

$db = new orm('localhost','user','pass','name_db');

$db->table('users')->insert([
    'username' => 'karim',
    'password' => md5('P123')
])->run();
```