Feature: Manage Survey Results data through API

  In order to offer a User access to the Survey Result resource via an hypermedia API
  As a client user
  I need to be able to retrieve, create, update and delete JSON encoded Survey resources.


  Background:
    Given there are Admin Users with the following details:
      | uid  | username | email          | password  | roles            |
      | u1   | peter    | peter@test.com | testpass  | ROLE_USER        |
      | u2   | john     | john@test.org  | johnpass  | ROLE_USER        |
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
    And there are Answer Results with the following details:
      | uid  | question | answer |
      | ar1  | q1       | an1    |
      | ar2  | q2       | an2    |
      | ar3  | q2       | an1    |
      | ar4  | q3       | an6    |
    And there are Survey Results with the following details:
      | uid  | user | survey   | answerResults      | date       | is_completed  |
      | sr1  | u1   | s1       | ar1,ar2            | 2016-08-18 | 1             |
    And there are Cron Survey with the following details:
      | uid | survey | date        | hourMin             | hourMax             | everyday |
      | cs1 | s1     | null        | 2016-08-18 06:00:00 | 2016-08-18 11:00:00 | 1        |
      | cs2 | s2     | 2016-08-17  | 2016-08-18 06:00:00 | 2016-08-18 11:00:00 | 0        |
      | cs3 | s2     | 2016-08-25  | 2016-08-18 06:00:00 | 2016-08-18 22:00:00 | 0        |
    And I am successfully logged in with username: "peter", and password: "testpass"
    And when consuming the endpoint I use the "headers/content-type" of "application/json"

  #answers scenarios
  Scenario: User cannot GET a Collection of Survey Results objects
    When I send a "GET" request to "/surveyresults"
    Then the response code should be 403

  # This test will pass only if the datetime() of this execution match with a cronsurvey entry
  # Change the cs3 entry
  Scenario: User can POST a new Survey Result object in respect with the survey questions and answers
    When I send a "POST" request to "/surveyresults" with body:
    """
    {
        "user":{"id":"u1"},
        "survey": {"id":"s2"},
        "answer_result":
        [
            {
                "question": "q2",
                "answer": "an2"
            },
            {
                "question": "q3",
                "answer": "an6"
            }
        ]
    }
    """
    Then the response code should be 201

  Scenario: User cannot POST a new Survey Result object with inexistent questions
    When I send a "POST" request to "/surveyresults" with body:
    """
    {
        "user":{"id":"u1"},
        "survey": {"id":"s2"},
        "answer_result":
        [
            {
                "question": "q5",
                "answer": "an2"
            },
            {
                "question": "q6",
                "answer": "an2"
            }
        ]
    }
    """
    Then the response code should be 403


  Scenario: User cannot POST a new Survey Result object with inexistent answers
    When I send a "POST" request to "/surveyresults" with body:
    """
    {
        "user":{"id":"u1"},
        "survey": {"id":"s2"},
        "answer_result":
        [
            {
                "question": "q2",
                "answer": "an8"
            },
            {
                "question": "q3",
                "answer": "an8"
            }
        ]
    }
    """
    Then the response code should be 400

  # here q3 doesnt own an2 but only an3
  Scenario: User cannot POST a new Survey Result object with questions and non related answers
    When I send a "POST" request to "/surveyresults" with body:
    """
    {
        "user":{"id":"u1"},
        "survey": {"id":"s2"},
        "answer_result":
        [
            {
                "question": "q2",
                "answer": "an2"
            },
            {
                "question": "q3",
                "answer": "an2"
            }
        ]
    }
    """
    Then the response code should be 400

    # here s2 have exactly 2 questions.
  Scenario: User cannot POST a new Survey Result object with more questions than the survey actually have
    When I send a "POST" request to "/surveyresults" with body:
    """
    {
        "user":{"id":"u1"},
        "survey": {"id":"s2"},
        "answer_result":
        [
            {
                "question": "q2",
                "answer": "an2"
            },
            {
                "question": "q3",
                "answer": "an6"
            },
            {
                "question": "q1",
                "answer": "an3"
            }
        ]
    }
    """
    Then the response code should be 400


  Scenario: User cannot POST a new Survey Result object with two answers for the same question.
    When I send a "POST" request to "/surveyresults" with body:
    """
    {
        "user":{"id":"u1"},
        "survey": {"id":"s2"},
        "answer_result":
        [
            {
                "question": "q2",
                "answer": "an2"
            },
            {
                "question": "q2",
                "answer": "an2"
            }
        ]
    }
    """
    Then the response code should be 400

  Scenario: User cannot PUT a Survey Result to modify the data
    When I send a "PUT" request to "/surveyresults/sr1"
    Then the response code should be 403

  Scenario: User cannot PATCH a Survey Result to modify the data
    When I send a "PATCH" request to "/surveyresults/sr1"
    Then the response code should be 403

  Scenario: User cannot DELETE a Survey Result
    When I send a "DELETE" request to "/surveyresults/cs1"
    Then the response code should be 403

  # This test will pass only if the datetime() of this execution match with a cronsurvey entry
  # Change the cs3 entry
  Scenario: User cannot POST a second new Survey Result object during the same day (this test just erase the data....)
    When I send a "POST" request to "/surveyresults" with body:
    """
    {
        "user":{"id":"u1"},
        "survey": {"id":"s2"},
        "answer_result":
        [
            {
                "question": "q2",
                "answer": "an2"
            },
            {
                "question": "q3",
                "answer": "an6"
            }
        ]
    }
    """
    Then the response code should be 201