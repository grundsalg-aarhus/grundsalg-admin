Feature: Visning af Delområder
  Som bruger af systemet har jeg  behov for at kunne se og redigere Delområder

  Background:
    Given the following delomraader exist:
      | kpl1 | kpl2 | kpl3 | kpl4 | Anvendelse          |
      | 11   | 22   | 33   | BL   | Erhverv             |
      | 11   | 22   | 33   | BL   | Åben-lav bebyggelse |
      | 11   | 22   | 33   | BL   | Tæt-lav bebyggelse  |

  @createSchema

  Scenario: Table overview shows
    When I am logged in with role "Admin"
    And I go to "/"
    And I follow "Delområder"
    Then the response status code should be 200
    And I should see "Delområder"
    And I should see "11.22.33.BL"

  Scenario: Sorting table columns doesn't produce an error
    When I am logged in with role "Admin"
    And I go to "/"
    And I follow "Delområder"
    And I follow "Anvendelse"
    And I follow "Mulighed for"
    And I follow "Lokalplan"
    Then the response status code should be 200
    And I should see "11.22.33.BL"

  Scenario: List should be searchable
    When I am logged in with role "Admin"
    And I go to "/"
    And I follow "Delområder"
    And I fill in the following:
      | query | Erhverv |
    And I press "Søg"
    Then I should see "Erhverv"
    And I should not see "Åben-lav bebyggelse"

  @dropSchema
  Scenario: Drop schema
