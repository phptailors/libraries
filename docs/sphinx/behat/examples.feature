Feature: Examples
  @singleton
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
