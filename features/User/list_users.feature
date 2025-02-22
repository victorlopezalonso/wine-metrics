Feature:
  In order to test the role-based access control
  As a user
  I want to list all users with different roles

  Scenario: It receives a successful response with a user with ADMIN_ROLE
    Given I am authenticated as "admin@example.com"
    When I send a GET request to "/api/v1/users"
    Then the response status code should be 200

  Scenario: It receives a forbidden response with a user with USER_ROLE
      Given I am authenticated as "john@example.com"
      When I send a GET request to "/api/v1/users"
      Then the response status code should be 403

  Scenario: It receives an unauthorized response without a token
      When I send a GET request to "/api/v1/users"
      Then the response status code should be 401
