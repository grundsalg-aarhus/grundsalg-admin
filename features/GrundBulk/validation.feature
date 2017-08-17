Feature: Validering  ved oprettelse af Grunde (flere)
  Grunde skal have et Lokalsamfund, for Salgsområder er det (Lokalplan) valgfrit. Da Grunde ved "opret flere" tager bl.a.
  Lokalsamfund fra det valgte Salgsomraade (Salgsområde->Lokalplan->Lokalsamfund) giver det problem, hvis der vælges et
  Salgsområde uden Lokalplan

  Background:
    Given the following salgsomraader exist:
      | Type           | Titel                   | Vej     | Annonceres | srid  | Geometry                                         | Lokalplan |
      | Parcelhusgrund | LP 826 Elsted/Elmehaven | Ølhaven | 1          | 25832 | POINT(577481.3338018466 6233798.938254305),25832 | 1         |
      | Parcelhusgrund | LP 827 Nopublic         | Nowhere | 0          | 25832 | POINT(577481.3338018466 6233798.938254305),25832 | null      |

  @createSchema

  Scenario: Admin can create multiple grunde from one form
    When I am logged in with role "Admin"
    And I go to "/?entity=vis_parcelhusgrund&action=list"
    And I follow "Opret Grunde (flere)"
    Then the response status code should be 200
    And I should see "Opret Grunde (flere)"
    When I fill in the following:
      | Salgsomr.                        | 2       |
      | Vej                              | Testvej |
      | grund_collection[grunde][0][mnr] | 10      |
    And I press "Gem"
    Then the response status code should be 200
    And I should see "Det valgte Salgsområde har ikke noget Lokalplan/Lokalsamfund tilknyttet."

  @dropSchema
  Scenario: Drop schema
