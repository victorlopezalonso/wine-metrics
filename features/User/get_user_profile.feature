Feature:
  In order to get user information
  As an authenticated user
  I want to get my user profile

  Scenario: It receives a successful response with a valid token
      Given I am authenticated as "john@example.com"
      When I send a GET request to "/api/v1/users/me"
      Then the response status code should be 200
      Then the JSON node "id" should exist
      Then the JSON node "name" should exist
      Then the JSON node "email" should exist
      Then the JSON node "roles" should exist
      Then the JSON node "createdAt" should exist

  Scenario: It receives an unauthorized response for a non-authenticated user
      When I send a GET request to "/api/v1/users/me"
      Then the response status code should be 401
      Then the JSON node "message" should exist