Feature: Public API
  In order to serve the front end
  As a client software developer
  I need to be able to retrieve "grunde" trough the API.

  Background:
    Given the following grunde exist:
      | username | password |
      | user-0   | pass-0   |
      | user-1   | pass-1   |

  @createSchema
  Scenario: Invalid sign in
    When I send a "POST" request to "/api/login_check" with parameters:
      | key       | value  |
      | _username | user-0 |
      | _password | pass   |

    Then the response status code should be 401

  Scenario: Valid sign in
    When I send a "POST" request to "/api/login_check" with parameters:
      | key       | value  |
      | _username | user-0 |
      | _password | pass-0 |

    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "token" should exist
    And the JSON node "user.username" should be equal to "user-0"

  @dropSchema
  Scenario: Drop schema
