Installation
============

The context library is split into two packages:

    - ``phptailors\context-interface`` - interface,
    - ``phptailors\context-library`` - implementation.

To install context as a runtime dependency, type:

.. code-block:: shell

   composer require "phptailors/context-library:dev-master"



Basic Usage
===========

The library provides :func:`Tailors\\Lib\\Context\\with` function, whose
typical use is like

.. code-block:: php

   use function Tailors\Lib\Context\with;
   // ...
   with($cm1, ...)(function ($arg1, ...) {
      // user's instructions to be executed within context ...
   });

The arguments ``$cm1, ...`` are subject of context management.
:func:`Tailors\\Lib\\Context\\with` accepts any value as an argument but only
certain types are context-managed out-of-the box. It supports most of the
standard `PHP resources`_ and objects that implement
:class:`Tailors\\Lib\\Context\\ContextManagerInterface`. A support for other
types and classes can be added with :ref:`context-library.context-factories`.

When calling :func:`Tailors\\Lib\\Context\\with` with arguments, for any
argument that is an instance of
:class:`Tailors\\Lib\\Context\\ContextManagerInterface`, its method
:method:`Tailors\\Lib\\Context\\ContextManagerInterface::enterContext`
gets invoked just before the user-provided callback is called, and
:method:`Tailors\\Lib\\Context\\ContextManagerInterface::exitContext`
gets invoked just after the user-provided callback returns (or throws an
exception). Whatever ``$cm#->enterContext()`` returns, is passed to the
user-provided callback as ``$arg#`` argument.


Simple Example
==============

A typical use of Python's `with-statement`_ is for automatic management of
opened file handles. Similar goals can be achieved with the
``phptailors/context-library`` and `PHP resources`_. In the following example
we'll open a file to only print its contents, and a resource context manager
will close it automatically when leaving the context.

.. literalinclude:: ../../examples/context-library/basic_with_usage.php
   :linenos:

.. literalinclude:: ../../examples/context-library/basic_with_usage.stdout
   :language: none

.. _with-statement: https://docs.python.org/reference/compound_stmts.html#with
.. _PHP resources: https://www.php.net/manual/en/language.types.resource.php

.. <!--- vim: set syntax=rst spell: -->
