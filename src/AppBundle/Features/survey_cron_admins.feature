Feature: Access CronSurvey data through API

  In order to offer a User access to the Cron Survey resources via an hypermedia API
  As an admin or super admin user
  I need to be able to retrieve, create, update or delete JSON encoded CronSurvey resources.


  Background:
    Given there are Admin Users with the following details:
      | uid  | username | email          | password  | roles            |
      | u1   | peter    | peter@test.com | testpass  | ROLE_USER        |
      | u2   | john     | john@test.org  | johnpass  | ROLE_ADMIN       |
      | u7   | alex     | alex@test.com  | adminpass | ROLE_SUPER_ADMIN |
    And there are Answers with the following details:
      | uid  | entitled | value |
      | an1  | TB       | 5     |
      | an2  | B        | 4     |
      | an3  | AB       | 3     |
      | an4  | M        | 2     |
      | an5  | P        | 1     |
      | an6  | N        | 0     |
    And there are Questions with the following details:
      | uid | entitled | answers     |
      | q1  | fatigue  | an1,an2,an3 |
      | q2  | sommeil  | an1,an2,an3 |
      | q3  | humeur   | an6         |
    And there are Surveys with the following details:
      | uid | title    | questions |
      | s1  | forme    | q1,q2     |
      | s2  | match    | q2,q3     |
    And there are Cron Survey with the following details:
      | uid | survey | date        | hourMin             | hourMax             | everyday |
      | cs1 | s1     | null        | 2016-08-18 06:00:00 | 2016-08-18 11:00:00 | 1        |
      | cs2 | s2     | 2016-08-17  | 2016-08-18 06:00:00 | 2016-08-18 11:00:00 | 0        |
    And I am successfully logged in with username: "john", and password: "johnpass"
    And when consuming the endpoint I use the "headers/content-type" of "application/json"

  #cron_surveys scenarios
  Scenario: Admins can GET a Collection of CronSurvey objects
    When I send a "GET" request to "/cronsurveys"
    Then the response code should be 200
    And the response header "Content-Type" should be equal to "application/json"
    And the response should contain json:
    """
    [
      {
        "id": "cs1",
        "survey": {
          "id": "s1",
          "title": "forme",
          "questions": []
        },
        "hour_min": "2016-08-18T06:00:00+0000",
        "hour_max": "2016-08-18T11:00:00+0000",
        "everyday": true
      },
      {
        "id": "cs2",
        "survey": {
          "id": "s2",
          "title": "match",
          "questions": []
        },
        "date": "2016-08-17T00:00:00+0000",
        "hour_min": "2016-08-18T06:00:00+0000",
        "hour_max": "2016-08-18T11:00:00+0000",
        "everyday": false
      }
     ]
    """
  Scenario: Admins can GET a CronSurvey object by Id
    When I send a "GET" request to "/cronsurveys/cs1"
    Then the response code should be 200
    And the response header "Content-Type" should be equal to "application/json"
    And the response should contain json:
    """
    {
      "id": "cs1",
      "survey": {
        "id": "s1",
        "title": "forme",
        "questions": []
      },
      "hour_min": "2016-08-18T06:00:00+0000",
      "hour_max": "2016-08-18T11:00:00+0000",
      "everyday": true
    }
    """

  Scenario: Admins can add a new CronSurvey's object
    When I send a "POST" request to "/cronsurveys" with body:
    """
    {
      "survey": {"id": "s1"},
      "hourMin": "2016-08-17 15:00:00",
      "hourMax": "2016-08-17 19:00:00",
      "everyday": true
    }
    """
    Then the response code should be 201

  Scenario: Admins can modify CronSurvey's data
    When I send a "PUT" request to "/cronsurveys/cs1" with body:
    """
    {
      "survey": {"id": "s1"},
      "hourMin": "2016-08-17 11:00:00",
      "hourMax": "2016-08-17 12:00:00",
      "everyday": true
    }
    """
    Then the response code should be 204

  Scenario: Admins can modify CronSurvey's data
    When I send a "PATCH" request to "/cronsurveys/cs1"
    """
    {
      "everyday": false,
      "survey": {"id": "s1"}
    }
    """
    Then the response code should be 204

  Scenario: Admins can modify CronSurvey's data
    When I send a "PATCH" request to "/cronsurveys/cs1"
    """
    {
      "everyday": false,
      "survey": {"id": "s1"}
    }
    """
    Then the response code should be 204

  Scenario: Admins can delete a CronSurvey
    When I send a "DELETE" request to "/cronsurveys/cs1"
    Then the response code should be 204

  Scenario: Admins cannot GET a none existent CronSurvey
    When I send a "GET" request to "/cronsurveys/cs100"
    Then the response code should be 403

  Scenario: Admins cannot PUT a none existent CronSurvey
    When I send a "PUT" request to "/cronsurveys/cs100"
    Then the response code should be 403

  Scenario: User cannot PATCH a none existent CronSurvey
    When I send a "PATCH" request to "/cronsurveys/cs100"
    Then the response code should be 403
