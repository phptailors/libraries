Feature: Examples
  @singleton-library
  Scenario Outline: Examples for phptailors/singleton-library
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                                    | stdout_file                                       | stderr_file                                           | exit_code |
      | "singleton-library/trivial_singleton.php"       | "singleton-library/trivial_singleton.stdout"      | "singleton-library/trivial_singleton.stderr"          | 0         |
      | "singleton-library/count_singleton.php"         | "singleton-library/count_singleton.stdout"        | "singleton-library/count_singleton.stderr"            | 0         |

  @singleton-testing
  Scenario Outline: Examples for phptailors/singleton-testing
    Given I tested <example_file> with PHPUnit
    Then I should see PHPUnit stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                                       | stdout_file                                          | stderr_file                                         | exit_code |
      | "singleton-testing/TrivialSingletonTest.php"       | "singleton-testing/TrivialSingletonTest.stdout"      | "singleton-testing/TrivialSingletonTest.stderr"     | 0         |
      | "singleton-testing/NonSingletonTest.php"           | "singleton-testing/NonSingletonTest.stdout"          | "singleton-testing/NonSingletonTest.stderr"         | 1         |

  @context-library
  Scenario Outline: Examples for phptailors/context-library
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                                    | stdout_file                                       | stderr_file                                       | exit_code |
      | "context-library/basic_with_usage.php"          | "context-library/basic_with_usage.stdout"         | "context-library/basic_with_usage.stderr"         | 0         |
      | "context-library/trivial_value_wrapper.php"     | "context-library/trivial_value_wrapper.stdout"    | "context-library/trivial_value_wrapper.stderr"    | 0         |
      | "context-library/default_context_manager.php"   | "context-library/default_context_manager.stdout"  | "context-library/default_context_manager.stderr"  | 0         |
      | "context-library/my_value_wrapper.php"          | "context-library/my_value_wrapper.stdout"         | "context-library/my_value_wrapper.stderr"         | 0         |
      | "context-library/my_context_factory.php"        | "context-library/my_context_factory.stdout"       | "context-library/my_context_factory.stderr"       | 0         |
      | "context-library/multiple_args.php"             | "context-library/multiple_args.stdout"            | "context-library/multiple_args.stderr"            | 0         |
      | "context-library/exception_handling.php"        | "context-library/exception_handling.stdout"       | "context-library/exception_handling.stderr"       | 1         |
      | "context-library/exit_true.php"                 | "context-library/exit_true.stdout"                | "context-library/exit_true.stderr"                | 0         |

  @error-library
  Scenario Outline: Examples for phptailors/error-library
    Given I executed doc example <example_file>
    Then I should see stdout from <stdout_file>
    And I should see stderr from <stderr_file>
    And I should see exit code <exit_code>

    Examples:
      | example_file                                    | stdout_file                                       | stderr_file                                       | exit_code |
      | "error-library/basic_example.php"               | "error-library/basic_example.stdout"              | "error-library/basic_example.stderr"              | 0         |
      | "error-library/custom_error_handler.php"        | "error-library/custom_error_handler.stdout"       | "error-library/custom_error_handler.stderr"       | 0         |
      | "error-library/simple_exception_thrower.php"    | "error-library/simple_exception_thrower.stdout"   | "error-library/simple_exception_thrower.stderr"   | 1         |
      | "error-library/caller_error_handler.php"        | "error-library/caller_error_handler.stdout"       | "error-library/caller_error_handler.stderr"       | 0         |
      | "error-library/caller_error_thrower.php"        | "error-library/caller_error_thrower.stdout"       | "error-library/caller_error_thrower.stderr"       | 1         |
