php-ldap
========

Object oriented interface for LDAP in PHP.

This project attempts to provide an object oriented interface for
working with LDAP in PHP.  It provides an abstraction layer for the
low-level functions in the ldap extension provided by PHP.  But it
aims to do way more!  A few of the major design goals of this library
are:

- Providing a mapping between DIT entries and domain data models,
- Enforcing compatibility with known schemata,
- Seamless browsing in the DIT.
