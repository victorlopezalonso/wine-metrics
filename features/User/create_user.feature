Feature:
  In order to have access to some resources
  As a non user
  I want to create a user account

  Scenario: It creates a user account for a non existing email
    When I send a POST request to "/api/v1/users" with body:
      """
      {
        "name": "non_existing_user",
        "email": "non_existing_email@example.com",
        "password": "test_password"
      }
      """
    Then the response status code should be 201

  Scenario: It fails to create a user account for an existing email
    When I send a POST request to "/api/v1/users" with body:
      """
      {
        "name": "john_doe",
        "email": "john@example.com",
        "password": "test_password"
      }
      """
    Then the response status code should be 400

  Scenario: It fails to create a user account for an invalid email
    When I send a POST request to "/api/v1/users" with body:
    """
    {
        "name": "john_doe",
        "email": "wrong_email",
        "password": "test_password"
    }
    """
    Then the response status code should be 422

  Scenario: It fails to create a user account for an invalid payload
    When I send a POST request to "/api/v1/users" with body:
    """
    {
        "name": "john_doe",
        "password": "test_password"
    }
    """
    Then the response status code should be 422