.. index::
   single: Singleton; TestingSingleton
   single: Lib; Singleton; TestingSingleton

.. _singleton-library.testing-singleton:

Testing Singleton
=================

To ensure that your class looks like and behaves like a singleton, you may use
facilities provided by ``phptailors/singleton-testing`` package:

.. code-block:: shell

   composer require --dev "phptailors/singleton-testing:dev-master"

Singleton testing is facilitated by
:class:`Tailors\\Testing\\Lib\\Singleton\\AssertIsSingletonTrait`.
Testing is as simple, as calling the
:method:`Tailors\\Testing\\Lib\\Singleton\\AssertIsSingletonTrait::assertIsSingleton`
method. The following example demonstrates how one test the ``TrivialSingleton``
developed in :ref:`lib.singleton.trivial_singleton.example` can be tested:

.. literalinclude:: ../../examples/singleton-testing/TrivialSingletonTest.php
  :linenos:

Running the above test yields:

.. literalinclude:: ../../examples/singleton-testing/TrivialSingletonTest.stdout
  :linenos:
  :language: none

The following example shows the effect of testing non-singleton classes with
the :method:`Tailors\\Testing\\Lib\\Singleton\\AssertIsSingletonTrait::assertIsSingleton`
method

.. literalinclude:: ../../examples/singleton-testing/NonSingletonTest.php
  :linenos:

Running the above test yields:

.. literalinclude:: ../../examples/singleton-testing/NonSingletonTest.stdout
  :linenos:
  :language: none

.. <!--- vim: set syntax=rst spell: -->
