.. _singleton-library.installation:

Installation
============

The singleton is split into three packages:

    - ``phptailors/singleton-interface`` - interface,
    - ``phptailors/singleton-library`` - implementation,
    - ``phptailors/singleton-testing`` - testing facilities.

To install the singleton implementation as a runtime dependency, type:

.. code-block:: shell

   composer require "phptailors/singleton-library:dev-master"

There is also a library that helps with writing unit tests for singletons
created with ``phptailors/singleton-library``. You may install the testing
library with:

.. code-block:: shell

   composer require --dev "phptailors/singleton-testing:dev-master"

.. _singleton-library.purpose:

Purpose
=======

To provide reusable boilerplate of the singleton design pattern.


.. <!--- vim: set syntax=rst spell: -->
