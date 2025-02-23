Feature:
  In order to check all the wines measurements
  As a user
  I want to list all wines

  Scenario: It should return a list of wines without measurements
    Given I am authenticated as "john@example.com"
    When I send a GET request to "/api/v1/wines"
    Then the response status code should be 200
    And the response should contain "id"
    And the response should contain "name"
    And the response should contain "year"
    And the response should not contain "measurements"

  Scenario: It should return a list of wines with measurements
    Given I am authenticated as "john@example.com"
    When I send a GET request to "/api/v1/wines?withMeasurements=true"
    Then the response status code should be 200
    And the response should contain "id"
    And the response should contain "name"
    And the response should contain "year"
    And the response should contain "measurements"
    And the response should contain "value"
    And the response should contain "unit"
    And the response should contain "date"
