.. index::
   single: Context; Trivial Value Wrapper
   single: Lib; Context; Trivial Value Wrapper
.. _context-library.trivial-value-wrapper:

Trivial Value Wrapper
=====================

The :class:`Tailors\\Lib\\Context\\TrivialValueWrapper` class is a dummy
context manager, which only passes value to user-provided callback. This is
also a default context manager used for types/values not recognized by the
context library. The following two examples are actually equivalent.

- explicitly used :class:`Tailors\\Lib\\Context\\TrivialValueWrapper`:

   .. literalinclude:: ../../examples/context-library/trivial_value_wrapper.php
      :linenos:
      :start-after: [test]
      :end-before: [/test]

- :class:`Tailors\\Lib\\Context\\TrivialValueWrapper` used internally as a
  fallback

   .. literalinclude:: ../../examples/context-library/default_context_manager.php
      :linenos:
      :start-after: [test]
      :end-before: [/test]

.. <!--- vim: set syntax=rst spell: -->
