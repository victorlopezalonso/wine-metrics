Feature:
  In order to add wine measurements
  As a user
  I want to create a new sensor

  Scenario: It creates a new sensor
    Given I am authenticated as "john@example.com"
    When I send a POST request to "/api/v1/sensors" with body:
      """
      {
        "name": "wine_sensor"
      }
      """
    Then the response status code should be 201

  Scenario: It fails to create a sensor with the same name
    Given I am authenticated as "john@example.com"
    When I send a POST request to "/api/v1/sensors" with body:
      """
      {
        "name": "existing_sensor"
      }
      """
    Then the response status code should be 400