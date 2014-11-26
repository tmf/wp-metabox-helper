Feature: Create Metaboxes and Metabox Items
    In order to edit some post's specific meta values
    As a developer
    I need to be able to create metaboxes with fields representing the meta values

    Background:
        Given I have a vanilla wordpress installation
            | name      | email                | username | password |
            | Metaboxes | tom.forrer@gmail.com | admin    | test     |
        And there are plugins
            | plugin                        | status  |
            | metabox-test/metabox-test.php | enabled |
        And I am logged in as "admin" with password "test"
        When I am on "/wp-admin/post-new.php"
        And I fill in "title" with "foo post"

    Scenario: Text post meta value
        When I fill in "text" with "bar"
        And I press "save"
        Then I should see "bar"

    Scenario: Dropdown post meta value
        When I fill in "dropdown" with "foo"
        And I press "save"
        Then I should see "Foo"