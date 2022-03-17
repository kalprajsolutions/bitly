# Laravel Bitly Advanced Package

A laravel package for generating [Bitly](https://bitly.com/) advanced short URLs and BITLINKS.

Bitly is the most widely trusted link management platform in the world. By using the Bitly API, you will exercise the full power of your links through automated link customization, mobile deep linking, and click analytics.

For more information see [BITLY DEV DOCS](https://dev.bitly.com/)

[![Build Status](https://github.com/Shivella/laravel-bitly/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/Shivella/laravel-bitly/actions) [![Latest Stable Version](https://poser.pugx.org/shivella/laravel-bitly/v/stable)](https://packagist.org/packages/shivella/laravel-bitly) [![License](https://poser.pugx.org/shivella/laravel-bitly/license)](https://packagist.org/packages/shivella/laravel-bitly) [![Total Downloads](https://poser.pugx.org/shivella/laravel-bitly/downloads)](https://packagist.org/packages/shivella/laravel-bitly)

## Requirements

Laravel 5.1 or Later
PHP 7.1 or Later
Bitly [Access Token](https://app.bitly.com/settings/api/ "Access Token")

## Installation

Installation is a quick 3 step process:

1. Download kalprajsolutions/bitly using composer
2. Enable the package in app.php
3. Configure your Bitly credentials
4. (Optional) Configure the package facade

### Step 1: Download kalprajsolutions/bitly using composer

Add **kalprajsolutions/bitly** by executing the command:

```
composer require kalprajsolutions/bitly
```

### Step 2: Enable the package in app.php

Register the Service in: **config/app.php**

```php
KalprajSolutions\Bitly\BitlyServiceProvider::class,
```

### Step 3: Configure Bitly credentials

```
php artisan vendor:publish --provider="KalprajSolutions\Bitly\BitlyServiceProvider"
```

Add this in you **.env** file

```
BITLY_ACCESS_TOKEN=your_secret_bitly_access_token
```

### Step 4 (Optional): Configure the package facade

Register the Bitly Facade in: **config/app.php**

```php
return [

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        // ...
        'Bitly' => KalprajSolutions\Bitly\Facade\Bitly::class,
    ],

];
```

## Quick Usage

```php
$url = app('bitly')->short('https://www.example.com/'); // http://bit.ly/abcdefg
```

Or if you want to use facade, add this in your class after namespace declaration:

```php
use Bitly;
```

Then you can use it directly by calling `Bitly::` like:

```php
$url = Bitly::short('https://www.example.com/'); // http://bit.ly/abcdefg
```

In quick usage you can also use Proxy to short the url asap using `->proxy()`

```php
$url = Bitly::proxy('user:pass@1.1.1.1:80')->short('https://www.example.com/'); // http://bit.ly/abcdefg
```

## Advance Usage

This Bitly package allow you to use advance bitlink attributes to customize bitly urls and proxies.

#### Guarding attributes

> Note: While using attribues you will have to provide `->url()` and `->get()` to retrive short url!

**URL**
You will have to provide long URL to this function which will be used to shorten the url.

```php
$url = Bitly::url('http://example.com')->get(); // http://bit.ly/nHcn3
```

**TITLE**
Its a short description that appears in the Bitly UI.
You can now set the title of the URL which you are shortning by passing title in title function

```php
$url = Bitly::url('http://example.com')
		->title('This will be the title')
		->get();
```

**PROXY**
Proxies can be now passed using proxy function which can be used to build bulk urls with proxies.

> Note: You can provide proxy URLs that contain a scheme, username, and password. For example, "http://username:password@192.168.16.1:10".

```php
$url = Bitly::url('http://example.com')
		->proxy('user:pass@1.1.1.1:80')
		->get();
```

**DOMAIN**
Customizing the domain requires that you have a custom domain attached to your Bitly account. The default value is bit.ly.
To brand your short links use domain attribute. Premium Bitly customers can set custom domain added in dashboard with `->domain()` . This is only for Premium Bitly Customers

```php
$url = Bitly::url('http://example.com')
		->domain('custom.com')
		->get();
```

**TAGS**
Set multiple tags with tags attributes. Tags must be provided in array.

```php
$url = Bitly::url('http://example.com')
		->tags([
		'First Tag',
		'Second Tag',
		])->get();
```

**GUID**
guid can be used to set your group id. GUID Identifies a group of users in your account. Every user will belong to at least one group within an organization. Most actions on our API will be on behalf of a group. Always verify your default group in Bitly and specify a group in the call with `->guid()` attribute.

```php
$url = Bitly::url('http://example.com')
		->guid('Ba1bc23dE4F')
		->get();
```

**PROXY**

Pass an associative array to specify HTTP proxies for specific URI schemes (i.e., "http", "https"). Provide a no key value pair to provide a list of host names that should not be proxied to.

> Note: You can provide proxy URLs that contain a scheme, username, and password. For example, "http://username:password@192.168.16.1:10".

```php
$url = Bitly::url('http://example.com')
		->proxy('user:pass@1.1.1.1:80')
		->get();

```

OR

```php
$url = Bitly::url('http://example.com')
		->proxy([
			'http'  => 'http://localhost:8125', // Use this proxy with "http"
			'https' => 'http://localhost:9124', // Use this proxy with "https"
		])
		->get();
```
