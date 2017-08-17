Feature: Oprettelse af Salgsområder
  Som bruger af systemet har jeg  behov for at kunne oprette Salgsområder

  Background:
    Given the following delomraader exist:
      | kpl1 | kpl2 | kpl3 | kpl4 | Anvendelse          |
      | 11   | 22   | 33   | BL   | Erhverv             |
      | 11   | 22   | 33   | BL   | Åben-lav bebyggelse |
      | 11   | 22   | 33   | BL   | Tæt-lav bebyggelse  |

  @createSchema

  Scenario: Admin can create a Salgsområde
    When I am logged in with role "Admin"
    And I go to "/"
    And I follow "Salgsområder"
    And I follow "Tilføj"
    Then the response status code should be 200
    And I should see "Opret Salgsområde"
    When I fill in the following:
      | Titel | Test område |
    And I press "Gem"
    Then the response status code should be 200

  Scenario: Editor can create a Salgsområde
    When I am logged in with role "Editor"
    And I go to "/"
    And I follow "Salgsområder"
    And I follow "Tilføj"
    Then the response status code should be 200
    And I should see "Opret Salgsområde"
#    When I fill in the following:
#      | Titel | Test område |
    And I press "Gem"
    Then the response status code should be 200

  Scenario: Reader cannot create a Salgsomraade
    When I am logged in with role "Reader"
    And I go to "/"
    And I follow "Salgsområder"
    Then I should not see "Tilføj"

  @dropSchema
  Scenario: Drop schema
