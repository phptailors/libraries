.. index::
   single: Singleton; UsingSingleton
   single: Lib; Singleton; UsingSingleton

.. _singleton-library.using-singleton:

Using Singleton
===============

Just use :class:`Tailors\\Lib\\Singleton\\SingletonTrait` in your class and the
class becomes a singleton. It is also recommended to annotate the class that it
implements :class:`Tailors\\Lib\\Singleton\\SingletonInterface`. The following
snippet provides a complete example of trivial singleton, that is an empty
class that is a singleton.


.. literalinclude:: ../../examples/singleton-library/trivial_singleton.php
   :linenos:
   :caption: Trivial Singleton example
   :name: lib.singleton.trivial_singleton.example

The :class:`Tailors\\Lib\\Singleton\\SingletonTrait` provides features that
shall appear in any typical singleton:

- public static :method:`Tailors\\Lib\\Singleton\\SingletonTrait::getInstance` method returning the single instance,
- private :method:`Tailors\\Lib\\Singleton\\SingletonTrait::__construct` method,
- private :method:`Tailors\\Lib\\Singleton\\SingletonTrait::__clone` method,
- public :method:`Tailors\\Lib\\Singleton\\SingletonTrait::__wakeup` method always throws :class:`Tailors\\Lib\\Singleton\\SingletonException`.

In addition:

- a protected method
  :method:`Tailors\\Lib\\Singleton\\SingletonTrait::initializeSingleton` is
  invoked from the private constructor when the single instance gets created;
  the class that includes the :class:`Tailors\\Lib\\Singleton\\SingletonTrait`
  may override the method to initialize the single instance on its own. The
  default implementation is just an empty method.


The complete functionality of the :class:`Tailors\\Lib\\Singleton\\SingletonTrait`
may be illustrated with the following example

.. literalinclude:: ../../examples/singleton-library/count_singleton.php
   :linenos:
   :caption: Usage of Singleton
   :name: lib.singleton.count_singleton.example

.. literalinclude:: ../../examples/singleton-library/count_singleton.stdout
   :linenos:
   :language: none

.. <!--- vim: set syntax=rst spell: -->
