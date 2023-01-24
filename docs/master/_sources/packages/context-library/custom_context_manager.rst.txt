.. index::
   single: Context; Custom Context Manager
   single: Lib; Context; Custom Context Manager
.. _context-library.custom-context-managers:

Custom Context Managers
=======================

A custom context manager can be created by implementing the
:class:`Tailors\\Lib\\Context\\ContextManagerInterface`. The new context
manager class must implement two methods:

   - :method:`Tailors\\Lib\\Context\\ContextManagerInterface::enterContext`:
     the value returned by this function is passed as an argument to
     user-provided callback when using :func:`Tailors\\Lib\\Context\\with`,
   - :method:`Tailors\\Lib\\Context\\ContextManagerInterface::exitContext`:
     the function accepts single argument ``$exception`` of type
     :phpclass:`\Throwable`, which can be ``null`` (if no exception occurred
     within a context); the function must return boolean value indicating
     whether it handled (``true``) or not (``false``) the ``$exception``.



Simple Value Wrapper
--------------------

In the following example we implement simple context manager, which wraps a
string and provides it as an argument to user-provided callback when using
:func:`Tailors\\Lib\\Context\\with`. Note, that there is a class
:class:`Tailors\\Lib\\Context\\TrivialValueWrapper` for very similar purpose
(except, it's not restricted to strings and it doesn't print anything).

Import symbols required for this example

.. literalinclude:: ../../examples/context-library/my_value_wrapper.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

The class implementation will be rather simple. Its ``enterContext()`` and
``exitContext()`` will just print messages to inform us that the context was
entered/exited.

.. literalinclude:: ../../examples/context-library/my_value_wrapper.php
   :linenos:
   :start-after: [MyValueWrapper]
   :end-before: [/MyValueWrapper]

The new context manager is ready to use. It may be tested as follows

.. literalinclude:: ../../examples/context-library/my_value_wrapper.php
   :linenos:
   :start-after: [test]
   :end-before: [/test]

Obviously, the expected output will be

.. literalinclude:: ../../examples/context-library/my_value_wrapper.stdout
   :linenos:
   :language: none

.. <!--- vim: set syntax=rst spell: -->
