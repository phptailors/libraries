.. index::
   single: Injector; UsingInjector
   single: Lib; Injector; UsingInjector

.. _injector-library.injector-concepts:

Basic Concepts
==============

An injector is responsible for providing appropriate objects or values to
clients (mostly to objects or functions in PHP). More precisely, injection is
just a process of assigning appropriate values to:

    - function or method parameters,
    - class or object properties,
    - other variables (static).

A **container** or **provider** is an object that keeps rules that define the
injection plan. These rules are sometimes referred to as **bindings** (binding
recipes to targets).

The term **binding** will be used to denote a configuration entry, that
specifies a way of providing an instance or a value to a client. In most cases
the binding will have a form of a Closure_ responsible for creation of an
object or retrieval of a requested value.

There are several ways that clients typically use to specify their
requirements. Probably, the most popular, is type hinting of function and
method parameters.

.. _Closure: https://www.php.net/manual/en/class.closure.php

.. <!--- vim: set syntax=rst spell: -->
