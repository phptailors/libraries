Installation
============

The error library is split into two packages

    - ``phptailors/error-interface`` - interface,
    - ``phptailors/error-library`` - implementation.

To install error library as a runtime dependency, type:

.. code-block:: shell

   php composer.phar require "phptailors/error-library:dev-master"

Purpose
=======

The main purpose of Error library is to provide handy utilities to control the
flow of PHP errors within an application. It is designed to play nicely with
our :ref:`Context Library <context-library>`, so one can temporarily redirect
PHP errors to custom handlers in one location without altering other parts of
code.

Basic Example
=============

In the following example we'll redirect errors from one invocation of a
problematic function to a no-op error handler. The example uses the
following functions

.. literalinclude:: ../../examples/error-library/basic_example.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

and the problematic function is

.. literalinclude:: ../../examples/error-library/basic_example.php
   :linenos:
   :start-after: [trouble]
   :end-before: [/trouble]

The function could normally cause some noise. For example, it could call
default or an application-wide error handler. Invoking it with ``@`` only
disables error messages, but the handler is still invoked. We can prevent this
by temporarily replacing original handler with the empty handler. This is
easily achieved with :ref:`Contexts <contextlib>` and
:class:`Tailors\\Lib\\Error\\EmptyErrorHandler`.

.. literalinclude:: ../../examples/error-library/basic_example.php
   :linenos:
   :start-after: [test]
   :end-before: [/test]

.. <!--- vim: set syntax=rst spell: -->
