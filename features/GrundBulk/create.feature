Feature: Oprettelse af Grunde (flere)
  Som bruger af systemet har jeg  behov for at kunne flere grunde på en gang

  Background:
    Given the following salgsomraader exist:
      | Type           | Titel                   | Vej     | Annonceres | srid  | Geometry                                         |
      | Parcelhusgrund | LP 826 Elsted/Elmehaven | Ølhaven | 1          | 25832 | POINT(577481.3338018466 6233798.938254305),25832 |
      | Parcelhusgrund | LP 827 Nopublic         | Nowhere | 0          | 25832 | POINT(577481.3338018466 6233798.938254305),25832 |

  @createSchema

  Scenario: Admin can create multiple grunde from one form
    When I am logged in with role "Admin"
    And I go to "/?entity=vis_parcelhusgrund&action=list"
    And I follow "Opret Grunde (flere)"
    Then the response status code should be 200
    And I should see "Opret Grunde (flere)"
    When I fill in the following:
      | Vej                              | Testvej |
      | grund_collection[grunde][0][mnr] | 10      |
    And I press "Gem"
    Then the response status code should be 200

  Scenario: Editor can create multiple grunde from one form
    When I am logged in with role "Editor"
    And I go to "/?entity=vis_parcelhusgrund&action=list"
    And I follow "Opret Grunde (flere)"
    Then the response status code should be 200
    And I should see "Opret Grunde (flere)"
    When I fill in the following:
      | Vej                              | Testvej |
      | grund_collection[grunde][0][mnr] | 10      |
    And I press "Gem"
    Then the response status code should be 200

  Scenario: Reader cannot see multiple create button
    When I am logged in with role "Reader"
    And I go to "/?entity=vis_parcelhusgrund&action=list"
    Then I should not see "Opret Grunde (flere)"

  Scenario: Reader cannot access multiple create form
    When I am logged in with role "Reader"
    And I go to "/?action=new&entity=grund_collection"
    Then the response status code should not be 200

  @dropSchema
  Scenario: Drop schema
