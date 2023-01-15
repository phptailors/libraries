.. index::
   single: Context; Context Factories
   single: Lib; Context; Context Factories
.. _context-library.context-factories:

Context Factories
=================

:ref:`context-library` has a concept of *Context Factory* (a precise name
should actually be *Context Manager Factory*). A *Context Factory* is an object
which takes a *value* and returns a new instance of
:class:`\\Tailors\\Lib\\Context\\ContextManagerInterface`, appropriate for
given *value*, or ``null`` if it won't handle the *value*. For example, the
:class:`Tailors\\Lib\\Context\\DefaultContextFactory` creates
:class:`Tailors\\Lib\\Context\\ResourceContextManager` for any
`PHP resource`_ and :class:`Tailors\\Lib\\Context\\TrivialValueWrapper` for any
other *value* (except for *values* that are already instances of
:class:`Tailors\\Lib\\Context\\ContextManagerInterface`).

:ref:`context-library` has a single stack of (custom) *Context Factories*
(:class:`Tailors\\Lib\\Context\\ContextFactoryStack`). It's empty by
default, so initially only the :class:`Tailors\\Lib\\Context\\DefaultContextFactory`
is used to generate *Context Managers*. A custom factory object can be pushed
to the top of the stack to get precedence over other factories.

Creating custom Context Factories
---------------------------------

A simplest way to create new *Context Factory* is to extend the
:class:`Tailors\\Lib\\Context\\AbstractManagedContextFactory`. The new
context factory must implement the
:method:`Tailors\\Lib\\Context\\ContextFactoryInterface::getContextManager`
method. The :class:`Tailors\\Lib\\Context\\AbstractManagedContextFactory` is
either a *Context Factory* and *Context Manager*. When an instance of
:class:`Tailors\\Lib\\Context\\AbstractManagedContextFactory` is passed
to :func:`Tailors\\Lib\\Context\\with`, it gets pushed to the top of
:class:`Tailors\\Lib\\Context\\ContextFactoryStack` when entering context
and popped when exiting (so the new *Context Factory* works for all nested
contexts).

Example with custom Managed Context Factory
-------------------------------------------

In the following example we'll wrap an integer value with an object named
``MyCounter``. Then, we'll create a dedicated *Context Manager*, named
``MyCounterManger``, to increment the counter when entering a context and
decrement when exiting. Finally, we'll provide *Context Factory* named
``MyContextFactory`` to recognize ``MyCounter`` objects and wrap them with
``MyCounterManager``.

For the purpose of example we need the following symbols to be imported

.. literalinclude:: ../../examples/context-library/my_context_factory.php
   :linenos:
   :start-after: [use]
   :end-before: [/use]

Our counter class will be as simple as

.. literalinclude:: ../../examples/context-library/my_context_factory.php
   :linenos:
   :start-after: [MyCounter]
   :end-before: [/MyCounter]

The counter manager shall just increment/decrement counter's value and print
short messages when entering/exiting a context.

.. literalinclude:: ../../examples/context-library/my_context_factory.php
   :linenos:
   :start-after: [MyCounterManager]
   :end-before: [/MyCounterManager]

Finally, comes the *Context Factory*.

.. literalinclude:: ../../examples/context-library/my_context_factory.php
   :linenos:
   :start-after: [MyContextFactory]
   :end-before: [/MyContextFactory]

We can now push an instance of ``MyContextFactory`` to the factory stack. To
push it temporarily, we'll create two nested contexts (outer and inner),
pass an instance of ``MyContextFactory`` to the outer context and do actual job
in the inner context.

.. literalinclude:: ../../examples/context-library/my_context_factory.php
   :linenos:
   :start-after: [test]
   :end-before: [/test]

It must be clear, that ``MyContextFactory`` is inactive in the outer
``with()`` (line 1). It only works when entering/exiting inner contexts (line 3
in the above snippet).

Following is the output from our example

.. literalinclude:: ../../examples/context-library/my_context_factory.stdout
   :linenos:
   :language: none

.. _PHP resource: https://www.php.net/manual/en/language.types.resource.php

.. <!--- vim: set syntax=rst spell: -->
