Feature: Public API
  In order to serve the front end
  As a client software developer
  I need to be able to retrieve "grunde" trough the API.

  Background:
    Given the following grunde with status exist:
      | Vej       | Husnummer | Bogstav | Salgsomraade | Annonceres | DatoAnnonce | Status       | SalgStatus       | geometry                                                                                                                                                                                                                                 |
      | Førstevej | 0         |         | 1            | 1          | -1 day      | Ledig        | Accepteret       | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832 |
      | Førstevej | 1         | A       | 1            | 1          | -1 day      | Fremtidig    | Ledig            | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832 |
      | Førstevej | 2         | C       | 1            | 1          | -1 day      | Ledig        | Ledig            | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832 |
      | Førstevej | 3         | B       | 1            | 1          | -1 day      | Solgt        | Ledig            | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832 |
      | Førstevej | 4         |         | 1            | 1          | -1 day      | Ledig        | Reserveret       | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832 |
      | Førstevej | 5         |         | 1            | 1          | -1 day      | Ledig        | Skøde rekvireret | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832 |
      | Førstevej | 6         |         | 1            | 1          | -1 day      | Auktion slut | Skøde rekvireret | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832 |
      | Førstevej | 7         |         | 1            | 1          | -1 day      | Ledig        | Solgt            | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832 |
      | Førstevej | 8         |         | 1            | 1          | -1 day      | Auktion slut | Solgt            | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832 |
      | Førstevej | 9         |         | 1            | 1          | -1 day      | Annonceret   | Ledig            | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832 |

  @createSchema

  Scenario: Response is OK and JSON
    When I send a "GET" request to "public/api/udstykning/1/grunde"
    Then the response status code should be 200
    And the response should be in JSON

  Scenario: Grunde are filtered by Salgsomraade
    When I send a "GET" request to "public/api/udstykning/1/grunde"
    Then the JSON node "grunde" should have 10 elements

  Scenario: Grunde are sorted correctly by address
    When I send a "GET" request to "public/api/udstykning/1/grunde"
    Then the JSON node "grunde[0].status" should be equal to "Solgt"
    Then the JSON node "grunde[1].status" should be equal to "Fremtidig"
    Then the JSON node "grunde[2].status" should be equal to "Ledig"
    Then the JSON node "grunde[3].status" should be equal to "Ledig"
    Then the JSON node "grunde[4].status" should be equal to "Reserveret"
    Then the JSON node "grunde[5].status" should be equal to "Solgt"
    Then the JSON node "grunde[6].status" should be equal to "Solgt"
    Then the JSON node "grunde[7].status" should be equal to "Solgt"
    Then the JSON node "grunde[8].status" should be equal to "Solgt"
    Then the JSON node "grunde[9].status" should be equal to "I udbud"

  @dropSchema
  Scenario: Drop schema
