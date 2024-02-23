# CI4-Auth

[![PHP](https://img.shields.io/badge/Language-PHP-8892BF.svg)](https://www.php.net/)
[![Bootstrap 5](https://img.shields.io/badge/Styles-Bootstrap%205-7952b3.svg)](https://www.getbootstrap.com/)
[![Bootstrap Icons](https://img.shields.io/badge/Icons-Bootstrap%20Icons-7952b3.svg)](https://icons.getbootstrap.com/)

CI4-Auth is a user, group, role and permission management library for Codeigniter 4.

CI4-Auth is based on the great [Myth-Auth](https://github.com/lonnieezell/myth-auth) library for Codeigniter 4 Due credits go to its author [Lonnie Ezell](https://github.com/lonnieezell) and the
team for this awesome work.

I started customizing Myth-Auth to meet my specific requirements but after a while I noticed that my changes got
quite large. I decided to build CI4-Auth based on Myth-Auth, changing and adding features I needed for my projects.

## Requirements

- PHP 8.1+
- CodeIgniter 4.4+
- [RobThree TwoFactorAuth](http://github.com/RobThree/TwoFactorAuth)

## Features

- Core Myth-Auth features
- Role objects are consistently called "role" in the code (e.g. tables, variables, classes)
- Added "Groups" as an addl. object, functioning just like roles
- Separated user controller functions from the Auth Controller
- 2FA based on RobThree's TwoFactorAuth
- Added views to manage users, groups, roles and permissions
- Added views to setup 2FA and for 2FA PIN login
- Added database seeders to create sample data
- Language support for English, German and Spanish
- Bootstrap 5 (CDN)
- Bootstrap Icons (CDN)
- Google Font "Open Sans" (CDN)

## Documentation

- [Installation](https://github.com/glewe/ci4-auth/blob/main/docs/installation.md)
- [2FA](https://github.com/glewe/ci4-auth/blob/main/docs/2fa.md)
- [Helpers](https://github.com/glewe/ci4-auth/blob/main/docs/helpers.md)

## Disclaimer

The CI4-Auth library is not perfect. It may very well contain bugs or things that can be done better. If you stumble upon such things, let me know 
via a [GitHub issue](https://github.com/glewe/ci4-auth/issues).
Otherwise I hope the library will help you. Feel free to change anything to meet the requirements in your environment.

Enjoy,
George Lewe
