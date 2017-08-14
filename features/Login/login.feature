Feature: Login
  Som bruger af systemet har jeg  behov for at kunne logge ind/ud

  Background:
    Given the following users exist:
      | username | email         | password | enabled |
      | admin    | admin@foo.com | admin    | yes     |
      | editor   | admin@foo.com | editor   | yes     |
      | reader   | admin@foo.com | reader   | yes     |

  @createSchema

  Scenario: Login and logout works
    When I go to "/"
    Then the response status code should be 200
    And I should see "Fagsystem log ind"
    And I fill in the following:
      | Brugernavn | admin |
      | Kodeord    | admin |
    And I press "Log ind"
    Then the response status code should be 200
    And I should see "Log ud"
    When I follow "Log ud"
    Then I should see "Fagsystem log ind"


  @dropSchema
  Scenario: Drop schema
