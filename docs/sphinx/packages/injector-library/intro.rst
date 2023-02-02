.. _injector-library.installation:

Installation
============

The injector is split into two packages:

    - ``phptailors/injector-interface`` - interface,
    - ``phptailors/injector-library`` - implementation.

To install the injector implementation as a runtime dependency, type:

.. code-block:: shell

   composer require "phptailors/injector-library:dev-master"

.. _injector-library.purpose:

Purpose
=======

To facilitate implementations of applications based on `Dependency Injection`_
design pattern.


.. _injector-library.introduction:

Introduction
============

According to its definition, `Dependency Injection`_ is a design pattern
in which an object or function receives other objects of functions that it
depends on. As a form of inversion of control, dependency injection aims to
separate the concerns of constructing objects and using them, leading to
loosely coupled programs.

Dependency injection involves four roles:

    - service,
    - client,
    - interface,
    - injector.

A **service** is any class which contains useful functionality. A **client** is
any class which uses services. Client should not know how their dependencies
are implemented, only their names and API. So, client should operate on
**interfaces**. The **injector**, sometimes also called an assembler,
container, provider or factory, introduces services to the client.

There are two main aspects of typical injector: configuration and injection.
The configuration is a process of defining **bindings**, i.e. providing the
plan of resolving dependencies (finding appropriate implementations of
interfaces) required by clients. There is a couple of dependency injection
libraries for PHP, including

    - `Symfony Service Container`_,
    - `Laravel Service Container`_,
    - `PHP-DI`_.

Here, we provide yet another library for similar purpose, but having some
specific features.


.. _Dependency Injection: https://en.wikipedia.org/wiki/Dependency_injection
.. _Laravel Service Container: https://laravel.com/docs/container
.. _Symfony Service Container: https://symfony.com/doc/current/service_container.html
.. _PHP-DI: https://php-di.org/

.. <!--- vim: set syntax=rst spell: -->
