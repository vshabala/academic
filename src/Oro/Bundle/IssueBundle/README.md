Requirements
Create a simple Bug Tracking System. Provide a bundle (or several bundles) for this functionality. One of the main entities of this bundle is “Issue”.
Data of Issue available in UI
summary
code
description
type (predefined types: Bug, Subtask, Task and Story)
priority (dictionary entity)
resolution (dictionary entity)
status (workflow step, OroWorkflowBundle: Open, In progress, Closed, Resolved, Reopened)
tags (dictionary entity from OroTagBundle)
reporter (user)
assignee (user)
related issues  
collaborators (users)
parent (applicable for Subtask)
children (applicable for Story)
notes
created
updated
Use cases
Issue must be configurable and extendable entity
User must be able to do CRUD operations on issues using UI and API
Issue must have a workflow:
Open: Start Progress -&gt; In progress, Resolve -&gt; Resolved, Close -&gt; Closed
In progress: Stop Progress -&gt; Open, Resolve -&gt; Resolved, Close -&gt; Closed
Closed: Reopen -&gt; Reopened
Resolved: Close -&gt; Closed, Repoen -&gt; Reopened
Reopened: Start Progress -&gt; In progress
Adding Subtask must be available for Story
Notes (OroNoteBundle) must be associated to Issue
Collaborators must contains all users that were at least once involved in task: user specified as assignee, user specified as reporter, user added a note (listening to this event must be a implemented as a process OroWorkflowBundle)
When note is added to issue, “updated” field of issue must be refreshed
Import and export must be available for Issues (OroImportExportBundle)
User must be able to search issues using global search
Search must be available by matching next fields: summary, code, type, priority, status, resolution, reporter, assignee
Custom template for search must be added
View page of issue must contain:
Information about issue
Collaborators
Link to Story for Subtask
Links to Subtasks for Story
Notes
Grid must contain columns: code, summary, type, priority, status, resolution, reporter, assignee, created, updated
Grid default sorting must be descending on field “updated”
Sorting in grid for columns priority and status must be based on it’s logical order (e.g. blocker (highest order) &gt; critical &gt; major &gt; trivial (lowest order))
On dashboard there must be a bar chart widget for issues by status
On dashboard there must be a widget with grid with last 10 active issues for current user (where user is collaborator). On this widget there must be a link to the main filtered grid to display the same data but without limit of 10 records.
Grid of issues assigned to user and reported by user must be added to user view page
On user view page there should be a button “Create Issue”, this button must open a popup with issue form and assignee in this form must be same as user view page
Ensure that notifications from OroSyncBundle on the grid of issues works when data some issue is created/updated/deleted by other user
Ensure that user can build reports based on issues
Issue must have Email activity
Add demo data fixtures for this project to be able to install it with filled data