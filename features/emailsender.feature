Feature: Send an email
    As a user of the email sending microservice
    I want to be able to send emails
    In order to communicate with my recipients

    Scenario: Sending a simple email
        Given I want send a mail via an email provider
        When I send an email with the following details:
        | recipient          | subject   | body                 |
        | example@domain.com | Send Test | This is a send test  |
        Then the email is successfully sent
        And the email is marked as sent with its sent date
