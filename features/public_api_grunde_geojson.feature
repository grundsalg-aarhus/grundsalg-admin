Feature: Public API
  In order to serve the front end
  As a client software developer
  I need to be able to retrieve "grunde" trough the API.

  Background:
    Given the following grunde exist:
      | Salgsomraade | Annonceres | DatoAnnonce | Geometry                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
      | 1            | 1          | -1 day      | POLYGON((577343.191172355 6231919.480812432,577376.2982372036 6231936.327383362,577413.2478654941 6231956.243329528,577343.2324015764 6232074.46926498,577325.5780489385 6232063.22155442,577291.1186656253 6232044.375390502,577274.6517145662 6232035.14726886,577315.6582982504 6231965.941355523,577343.191172355 6231919.480812432)),25832                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
      | 2            | 1          | -1 day      | POLYGON((580660.8818873378 6236228.713680544,580661.7394551445 6236232.552899082,580675.0894770599 6236298.669441251,580676.2768786384 6236304.548244638,580672.8301157227 6236310.577017498,580668.7236852633 6236311.946738696,580664.5677797381 6236313.136496524,580616.4450324264 6236308.417457071,580624.3610429505 6236285.692082755,580633.8849931125 6236258.37764253,580643.2605180771 6236231.453122939,580650.2035189744 6236230.843247077,580660.8818873378 6236228.713680544)),25832                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |
      | 2            | 1          | -1 day      | POLYGON((565762.1997089473 6225265.705169221,565770.0909819386 6225260.356257977,565792.6598577769 6225287.340765359,565743.8279678557 6225324.353231578,565724.6233964902 6225304.647242676,565762.1997089473 6225265.705169221)),25832                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
      | 2            | 1          | -1 day      | POLYGON((577413.2478654941 6231956.243329528,577416.3565487937 6231957.912989673,577479.9979750707 6231993.925659399,577409.3228436093 6232113.26136896,577408.283867228 6232112.47152973,577377.4361637164 6232095.734936415,577343.2324015764 6232074.46926498,577413.2478654941 6231956.243329528)),25832                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
      | 2            | 1          | -1 day      | POLYGON((561503.4602595221 6222216.785767948,561656.8824384945 6222220.055102484,561656.5690964112 6222266.055739188,561646.6081165016 6222272.61440419,561503.1799008161 6222269.555026918,561503.4602595221 6222216.785767948)),25832                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |


  Scenario: GeoJSON is returned correctly
    When I send a "GET" request to "public/api/udstykning/1/grunde/geojson"
    Then the response status code should be 200
    Then the response should be in JSON
    Then the JSON node "type" should be equal to "FeatureCollection"
    Then the JSON node "crs.type" should be equal to "link"
    Then the JSON node "crs.properties.href" should be equal to "http://spatialreference.org/ref/epsg/25832/proj4/"
    Then the JSON node "crs.properties.type" should be equal to "proj4"
    Then the JSON node "features[0].geometry.type" should be equal to "Polygon"
    Then the JSON node "features[0].geometry.coordinates[0][0][0]" should be equal to "577343.19117235,6231919.4808124"

  @dropSchema
  Scenario: Drop schema
