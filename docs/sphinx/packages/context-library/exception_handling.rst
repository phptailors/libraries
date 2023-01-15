.. index::
   single: Context; Exception Handling
   single: Lib; Context; Exception Handling
.. _context-library.exception-handling:

Exception Handling
==================

Default behavior
----------------

One of the main benefits of using contexts is their "unroll" feature which
works even when an exception occurs in a user-provided callback. This means,
that :method:`Tailors\\Lib\\Context\\ContextManagerInterface::exitContext` is
invoked, even if the user's code execution gets interrupted by an exception. To
illustrate this, we'll slightly modify the example from the section named
:ref:`context-library.multiple-context-arguments`. We'll use same ``MyInt`` objects as context
managers for all context arguments

.. literalinclude:: ../../examples/context-library/exception_handling.php
   :linenos:
   :start-after: [MyInt]
   :end-before: [/MyInt]

Instead of doing anything useful, we'll just throw our custom exception
``MyException`` from the context (later):

.. literalinclude:: ../../examples/context-library/exception_handling.php
   :linenos:
   :start-after: [MyException]
   :end-before: [/MyException]

The exception handling and unroll process may be demonstrated with the
following snippet. We expect all the values 1, 2, and 3 to be printed at enter
and the same numbers in reversed order printed when context exits. Finally, we
should also receive ``MyException``.

.. literalinclude:: ../../examples/context-library/exception_handling.php
   :linenos:
   :start-after: [try-catch]
   :end-before: [/try-catch]

The outputs from above snippet shall be

- stdout:

   .. literalinclude:: ../../examples/context-library/exception_handling.stdout
      :linenos:
      :language: none

- stderr:

   .. literalinclude:: ../../examples/context-library/exception_handling.stderr
      :linenos:
      :language: none


Handling exceptions in exitContext
----------------------------------

If one of the context managers returns ``true`` from its
:method:`Tailors\\Lib\\Context\\ContextManagerInterface::exitContext`,
all the remaining context managers will receive ``null`` as ``$exception``
argument and the exception will be treated as handled (it will not be
propagated to the context caller). To demonstrate this, let's consider the
following modified ``MyInt`` class

.. literalinclude:: ../../examples/context-library/exit_true.php
   :linenos:
   :start-after: [MyInt]
   :end-before: [/MyInt]

The object may be configured to return ``true`` or ``false``. What happens when
one of the context managers returns ``true``, may be explained by the following
snippet

.. literalinclude:: ../../examples/context-library/exit_true.php
   :linenos:
   :start-after: [test]
   :end-before: [/test]

When unrolling, the objects ``MyInt(4)`` and ``MyInt(3, true)`` receive an
instance of ``MyException`` as ``$exception``, then ``MyInt(3, true)`` returns
true and the remaining objects ``MyInt(2)`` and ``MyInt(1)`` receive ``null``
as ``$exception``. The exception thrown from user-provided callback is not
propagated to the outside. The code from the above snippet runs without an
exception and outputs the following text


.. literalinclude:: ../../examples/context-library/exit_true.stdout
   :linenos:
   :language: none

.. <!--- vim: set syntax=rst spell: -->
