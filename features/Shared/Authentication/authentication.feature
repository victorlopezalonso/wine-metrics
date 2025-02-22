Feature: Authentication
  In order to access protected resources
  As a user
  I need to authenticate and receive a valid JWT token or refresh an existing one

  Scenario: Successfully authenticate with valid credentials
    When I send a POST request to "/api/v1/authentication" with body:
      """
      {
        "username": "john@example.com",
        "password": "password123"
      }
      """
    Then the response status code should be 200
    And the JSON node "token" should exist
    And the JSON node "refreshToken" should exist
    And the JSON node "refreshTokenExpiration" should exist

  Scenario: Successfully refresh a valid token
    Given I have a valid refresh token for user "john@example.com"
    When I send a PUT request to "/api/v1/authentication" with body:
      """
      {
      "refreshToken": "valid_refresh_token"
      }
      """
    Then the response status code should be 200
    And the JSON node "token" should exist
    And the JSON node "refreshToken" should exist
    And the JSON node "refreshTokenExpiration" should exist

  Scenario: Refresh token fails if the token is expired
    Given I have an expired refresh token for user "john@example.com"
    When I send a PUT request to "/api/v1/authentication" with body:
      """
      {
        "refresh_token": "expired_refresh_token"
      }
      """
    Then the response status code should be 401

  Scenario: Authentication fails with invalid credentials
    When I send a POST request to "/api/v1/authentication" with body:
      """
      {
        "username": "user@example.com",
        "password": "wrongpassword"
      }
      """
    Then the response status code should be 404


