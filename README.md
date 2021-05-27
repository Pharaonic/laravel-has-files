<p align="center"><a href="https://pharaonic.io" target="_blank"><img src="https://raw.githubusercontent.com/Pharaonic/logos/main/has-files.jpg"></a></p>

<p align="center">
<a href="https://github.com/Pharaonic/laravel-has-files" target="_blank"><img src="http://img.shields.io/badge/source-pharaonic/laravel--has--files-blue.svg?style=flat-square" alt="Source"></a> <a href="https://packagist.org/packages/pharaonic/laravel-has-files" target="_blank"><img src="https://img.shields.io/packagist/v/pharaonic/laravel-has-files?style=flat-square" alt="Packagist Version"></a><br>
<a href="https://laravel.com" target="_blank"><img src="https://img.shields.io/badge/Laravel->=6.0-red.svg?style=flat-square" alt="Laravel"></a> <img src="https://img.shields.io/packagist/dt/pharaonic/laravel-has-files?style=flat-square" alt="Packagist Downloads"> <img src="http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Source">
</p>


#### Laravel files provides a quick and easy way to link files with a model.

###### 



## Install

Install the latest version using [Composer](https://getcomposer.org/):

```bash
$ composer require pharaonic/laravel-has-files
```

then publish the migration & config files
```bash
# if you didn't publish Pharaonic\laravel-uploader before.

$ php artisan vendor:publish --tag=laravel-uploader
```

```bash
$ php artisan vendor:publish --tag=laravel-has-files
$ php artisan migrate
```



## Usage
- [Configuration](#config)
- [Including it in a Model](#INC)
- [How to use](#HTU)
- [Uploader Options](#UP)



<a name="config"></a>

#### Configuration
```php
/**
*	config/Pharaonic/files.php
*	default files fields
*
*	files 		=> ['image', 'picture', 'cover', 'thumbnail', 'video', 'audio', 'file']
*/
```



<a name="INC"></a>

#### Including it in a Model
```php
// An example
// Using HasFiles in Person Model
...
use Pharaonic\Laravel\Files\HasFiles;
use Pharaonic\Laravel\Helpers\Traits\HasCustomAttributes;

class Person extends Model
{
    use HasCustomAttributes, HasFiles;
    
    protected $filesAttributes  = ['passport']; // if not in defaults in config file
    
    protected $filesOptions 	= [ // optional
        'passport'	=> [
            'private'	=> true,
            // 'visitable'	=> true,
            'directory'	=> '/papers/passports'
        ]
    ];
    ...
}
```



<a name="HTU"></a>

#### How to use

```php
// Retrive Person
$person = Person::find(1); 		        // Model
$person->passport = $request->myFile;   // Request Input File + Uploading it
echo $person->passport->url; 	        // Getting passport file URL

// Create Person
$person = new Person;
...
$person->passport = $request->myFile;
$person->save();
echo $person->passport->url;



// Delete Files
$person->delete(); 				// Delete Person with all related files
// OR
$person->clearFiles();			// Delete all related files
// OR
$person->passport->delete();	// Delete file

```



<a name="UP"></a>

#### Uploader Options

###### $person->passport is retrieving Uploader Object.

###### That's allow for us use all [Pharaonic/laravel-uploader](https://github.com/Pharaonic/laravel-uploader) options.



```php
$file = $person->passport;
```
```php
// Information
echo $file->hash; // File's Hash
echo $file->name; // File's Name
echo $file->path; // File's Path
echo $file->size; // File's Size in Bytes
echo $file->readableSize(); // File's Readable Size [B, KB, MB, ...] (1000)
echo $file->readableSize(false); // File's Readable Size [B, KiB, MiB, ...] (1024)
echo $file->extension; // File's Extension
echo $file->mime; // File's MIME

echo $file->visits; // File's visits (Visitable File)


// Getting URL
echo $file->url; // Getting Uploaded File's URL


// Deleting The File
$file->delete();


// Permits (Private File)
$permits = $file->permits; // Getting Permits List
$permitted = $file->isPermitted($user); // Checking if permitted (App\User)

$file->permit($user, '2021-02-01'); // Permitting a user
$file->forbid($user); // Forbidding a user
```





## License

[MIT license](LICENSE.md)
