# email_picker_from_townpage

## Description

A script that scrapes company information (company name, phone number, email address, address, etc.) across the country from the Japanese phonebook page ( [i town page](https://itp.ne.jp/) ).

The following information is collected and output in csv format.

* Company name
* Mail address
* Phone number
* Street address
* Prefecture ID


*Please contact me anytime if you have a problem or request! My information is posted at the bottom of this document.*

## Requirements

* PHP7.x

## Dependencies

* [Composer](https://getcomposer.org/)

## Installation

After cloning the source code of our repository, please execute the following command.

```bash
$ composer install
```

## Usage

```bash
$ php ./index.php | tee addresses.txt
```

OR

```bash
$ ./index.php | tee addresses.txt
```

## Configuration

There is no configuration file.

## Relase Note

| date       | version | note           |
| ---        | ---     | ---            |
| 2019-04-03 | 0.1     | first release. |


## License

This software is released under the MIT License, see LICENSE.


## Author Information

[genzouw](https://genzouw.com)

* Twitter   : @genzouw ( https://twitter.com/genzouw )
* Facebook  : genzouw ( https://www.facebook.com/genzouw )
* Gmail     : genzouw@gmail.com
