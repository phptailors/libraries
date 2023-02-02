.. index::
   single: Injector; UsingInjector
   single: Lib; Injector; UsingInjector

.. _injector-library.injector-concepts:

Basic Concepts
==============

Main responsibility of injector is to provide appropriate objects or values to
clients. In context of PHP, an object or function can be considered a client.
Diving into details, we quickly conclude, that technically, the process of
injecting is actually a process of assigning values/instances to:

    - function or method parameters,
    - class or object properties,
    - other variables (static).

Here we'll introduce a bit of informal terminology that shall help
understand our approach to dependency injection and the philosophy of
:ref:`Injector Library <injector-library>`.

The term **bindings** will be used to denote a configuration entry, that
specifies a way of providing an instance or a value to a client. In most cases
the binding will have a form of a Closure_ responsible for creation or
retrieval of a required value.

.. _Closure: https://www.php.net/manual/en/class.closure.php

.. <!--- vim: set syntax=rst spell: -->
