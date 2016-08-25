Feature: Manage Survey data through API

  In order to offer a User access to the Survey resource via an hypermedia API
  As a basic user or an admin user
  I need to be able to retrieve JSON encoded Survey resources.


  Background:
    Given there are Admin Users with the following details:
      | uid  | username | email          | password  | roles            |
      | u1   | peter    | peter@test.com | testpass  | ROLE_USER        |
      | u2   | john     | john@test.org  | johnpass  | ROLE_USER        |
      | u3   | mich     | mich@test.org  | michpass  | ROLE_ADMIN       |
      | u7   | alex     | alex@test.com  | adminpass | ROLE_SUPER_ADMIN |
    And there are Answers with the following details:
      | uid   | entitled | value |
      | an1   | TB       | 5     |
      | an2   | B        | 4     |
      | an3   | AB       | 3     |
      | an4   | M        | 2     |
      | an5   | P        | 1     |
      | an6   | N        | 0     |
    And there are Questions with the following details:
      | uid | entitled | answers      |
      | q1  | fatigue  | an1,an2,an3  |
      | q2  | sommeil  | an1,an2,an3  |
      | q3  | humeur   | an6          |
    And there are Surveys with the following details:
      | uid | title    | questions |
      | s1  | forme    | q1,q2     |
      | s2  | match    | q2,q3     |
    And I am successfully logged in with username: "peter", and password: "testpass"
    #And I am successfully logged in with username: "mich", and password: "michpass"
    And when consuming the endpoint I use the "headers/content-type" of "application/json"

  #answers scenarios
  Scenario: User can GET a Collection of Answers objects
    When I send a "GET" request to "/answers"
    Then the response code should be 200
    And the response header "Content-Type" should be equal to "application/json"
    And the response should contain json:
    """
    [{
      "id": "an1",
      "entitled": "TB",
      "value": "5"
    },
    {
      "id": "an2",
      "entitled": "B",
      "value": "4"
    },
    {
      "id": "an3",
      "entitled": "AB",
      "value": "3"
    },
    {
      "id": "an4",
      "entitled": "M",
      "value": "2"
    },
    {
      "id": "an5",
      "entitled": "P",
      "value": "1"
    },
    {
      "id": "an6",
      "entitled": "N",
      "value": "0"
    }]
    """
#
  Scenario: User can GET an individual Answer by ID
    When I send a "GET" request to "/answers/an1"
    Then the response code should be 200
    And the response header "Content-Type" should be equal to "application/json"
    And the response should contain json:
    """
    {
      "id": "an1",
      "entitled": "TB",
      "value": "5"
    }
    """
#
  Scenario: User cannot add a new Answer
    When I send a "POST" request to "/answers" with body:
    """
    {
      "entitled": "TB",
      "value": "5"
    }
    """
    Then the response code should be 403
#
  Scenario: User cannot modify Answer's data
    When I send a "PUT" request to "/answers/an1" with body:
    """
    {
      "entitled": "TB",
      "value": "5"
    }
    """
    Then the response code should be 403
#
  Scenario: User cannot modify Answer's data
    When I send a "PATCH" request to "/answers/an1"
    """
    {
      "entitled": "TB"
    }
    """
    Then the response code should be 403
#
  Scenario: User cannot add a new Answer
    When I send a "DELETE" request to "/answers/an1"
    Then the response code should be 403
#
  Scenario: User cannot GET a none existent Answer
    When I send a "GET" request to "/answers/an100"
    Then the response code should be 403
#
  Scenario: User cannot PUT a none existent Answer
    When I send a "PUT" request to "/answers/an100"
    Then the response code should be 403
#
  Scenario: User cannot PATCH a none existent Answer
    When I send a "PATCH" request to "/answers/an100"
    Then the response code should be 403
#
#
  #questions scenarios
  Scenario: User can GET a Collection of Questions objects
    When I send a "GET" request to "/questions"
    Then the response code should be 200
    And the response header "Content-Type" should be equal to "application/json"
    And the response should contain json:
    """
    [{
      "id": "q1",
      "entitled": "fatigue",
      "answers": [
        {
          "id": "an1",
          "entitled": "TB",
          "value": "5"
        },
        {
          "id": "an2",
          "entitled": "B",
          "value": "4"
        },
        {
          "id": "an3",
          "entitled": "AB",
          "value": "3"
        }
      ]
    },
    {
      "id": "q2",
      "entitled": "sommeil",
      "answers": [
        {
          "id": "an1",
          "entitled": "TB",
          "value": "5"
        },
        {
          "id": "an2",
          "entitled": "B",
          "value": "4"
        },
        {
          "id": "an3",
          "entitled": "AB",
          "value": "3"
        }
      ]
    },
    {
      "id": "q3",
      "entitled": "humeur",
      "answers": [
        {
          "id": "an6",
          "entitled": "N",
          "value": "0"
        }
      ]
    }]
    """
#
  Scenario: User can GET an individual Question by ID
    When I send a "GET" request to "/questions/q1"
    Then the response code should be 200
    And the response header "Content-Type" should be equal to "application/json"
    And the response should contain json:
    """
    {
      "id": "q1",
      "entitled": "fatigue",
      "answers": [
        {
          "id": "an1",
          "entitled": "TB",
          "value": "5"
        },
        {
          "id": "an2",
          "entitled": "B",
          "value": "4"
        },
        {
          "id": "an3",
          "entitled": "AB",
          "value": "3"
        }
      ]
    }
    """
  Scenario: User cannot add a new Question
    When I send a "POST" request to "/questions" with body:
    """
    {
      "entitled": "fatigue",
      "answers": [{"id": "an1"},{"id": "an2"},{"id": "an3"}]
    }
    """
    Then the response code should be 403
#
  Scenario: User cannot modify Question's data
    When I send a "PUT" request to "/questions/q1" with body:
    """
    {
      "entitled": "fatigue",
      "answers": [{"id": "an1"},{"id": "an2"},{"id": "an3"}]
    }
    """
    Then the response code should be 403
#
  Scenario: User cannot modify Question's data
    When I send a "PATCH" request to "/questions/q1"
    """
    {
      "entitled": "question1patched"
    }
    """
    Then the response code should be 403
#
  Scenario: User cannot delete a Question
    When I send a "DELETE" request to "/questions/q1"
    Then the response code should be 403
#
  Scenario: User cannot GET a none existent Question
    When I send a "GET" request to "/questions/q100"
    Then the response code should be 403
#
  Scenario: User cannot PUT a none existent Question
    When I send a "PUT" request to "/questions/q100"
    Then the response code should be 403
#
  Scenario: User cannot PATCH a none existent Question
    When I send a "PATCH" request to "/questions/q100"
    Then the response code should be 403
#
#
  # surveys scenarios
  Scenario: User cannot GET a Collection of Surveys objects without cronSurvey info
    When I send a "GET" request to "/surveys"
    Then the response code should be 403
    And the response header "Content-Type" should be equal to "application/json"
    And the response should contain json:
    """
    {"code":403,"message":"Any survey is available yet."}
    """
#
  Scenario: User cannot GET a Survey by ID without cronSurvey info
    When I send a "GET" request to "/surveys/s1"
    Then the response code should be 403
    And the response header "Content-Type" should be equal to "application/json"
    And the response should contain json:
    """
    {"code":403,"message":"Any survey is available yet."}
    """
#
  Scenario: User cannot add a new Survey
    When I send a "POST" request to "/surveys" with body:
    """
    {
      "title": "new_one",
      "questions": [{"id": "q1"},{"id": "q2"},{"id": "q3"}]
    }
    """
    Then the response code should be 403
#
  Scenario: User cannot modify Survey's data
    When I send a "PUT" request to "/surveys/s1" with body:
    """
    {
      "title": "new_one",
      "questions": [{"id": "q1"},{"id": "q2"},{"id": "q3"}]
    }
    """
    Then the response code should be 403
    And the response should contain json:
    """
    {"code":403,"message":"Any survey is available yet."}
    """
#
  Scenario: User cannot modify Survey's data
    When I send a "PATCH" request to "/surveys/s1"
    """
    {
      "title": "question1patched"
    }
    """
    Then the response code should be 403
    And the response should contain json:
    """
    {"code":403,"message":"Any survey is available yet."}
    """
#
  Scenario: User cannot delete a Survey
    When I send a "DELETE" request to "/surveys/s1"
    Then the response code should be 403
    And the response should contain json:
    """
    {"code":403,"message":"Any survey is available yet."}
    """
#
  Scenario: User cannot GET a none existent Survey
    When I send a "GET" request to "/surveys/s100"
    Then the response code should be 403
#
  Scenario: User cannot PUT a none existent Survey
    When I send a "PUT" request to "/surveys/s100"
    Then the response code should be 403
#
  Scenario: User cannot PATCH a none existent Survey
    When I send a "PATCH" request to "/surveys/s100"
    Then the response code should be 403
