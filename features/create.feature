@javascript
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
        And I am on "/wp-admin/post-new.php"
        And I fill in the post title with "foo post"

    Scenario: Text post meta value
        When I fill in "Metatext" with "Some metavalue"
        And I press "Save Draft"
        And I wait for the notification message "Post draft updated"
        Then the "Metatext" field should contain "Some metavalue"

    Scenario: Dropdown post meta value
        When I select "foo" from the dropdown field "dropdown"
        And I press "Save Draft"
        And I wait for the notification message "Post draft updated"
        Then the "dropdown" field should contain "foo"