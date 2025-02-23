Feature:
  In order to add wine measurements
  As a user
  I want to list all sensors

  Scenario: It should return a list of sensors
    Given I am authenticated as "john@example.com"
    When I send a GET request to "/api/v1/sensors"
    Then the response status code should be 200
    And the response should contain "id"
    And the response should contain "name"
