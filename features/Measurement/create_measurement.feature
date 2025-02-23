Feature:
  In order to add wine information
  As a user
  I want to create a new measurement

  Scenario: It creates a new measurement for an existing combination of wine and sensor
    Given I am authenticated as "john@example.com"
    When I send a POST request to "/api/v1/measurements" with body:
      """
      {
        "wineId": 1,
        "sensorId": 1,
        "value": "10",
        "unit": "C"
      }
      """
    Then the response status code should be 201

  Scenario: It fails to create a new measurement for a non existing wine
    Given I am authenticated as "john@example.com"
    When I send a POST request to "/api/v1/measurements" with body:
      """
      {
        "wineId": 0,
        "sensorId": 1,
        "value": "10",
        "unit": "C"
      }
      """
    Then the response status code should be 404

  Scenario: It fails to create a new measurement for a non existing sensor
    Given I am authenticated as "john@example.com"
    When I send a POST request to "/api/v1/measurements" with body:
      """
      {
        "wineId": 1,
        "sensorId": 0,
        "value": "10",
        "unit": "C"
      }
      """
    Then the response status code should be 404

  Scenario: It fails to create a new measurement for a wrong payload
    Given I am authenticated as "john@example.com"
    When I send a POST request to "/api/v1/measurements" with body:
      """
      {
      }
      """
    Then the response status code should be 422

