OWEditorial
===========

**oweditorial** provides an editorial workflow for eZPublish, based on object states.

#Install
1) Put content on extension in `extension/oweditorial` folder :
```
git clone https://github.com/Open-Wide/OWEditorial.git ./oweditorial
```
or
```
git submodule add https://github.com/Open-Wide/OWEditorial.git extension/oweditorial
```

2) Activate extension :
Add the following to your settings/override/site.ini.append.php file:
```
[ExtensionSettings]
ActiveExtensions[]=oweditorial
```

3) Regenerate autoloads

4) Clear cache

#Setup

##Set object states
1) Create an object state group via "Setup > States > Create new"
*example : `myworkflow`*

2) Add a state named `none` in this group.

3) Add your custom states in this group.
*example : `pending`, `validated`, `refused`, `published`, `archived`*

##Set actions
1) Create a file `settings/override/oweditorial.ini.append.php` to add your own settings.

2) You must enable your workflow in this file :
```
[Workflows]
Workflows[]
Workflows[]=myworkflow
```

3) Set a default state for the new objects (in this example, the state "pending" will be setted on each new published object) :
```
[myworkflow]
FirstState=pending
```

4) To define which action is required to set a new state, set your actions like this :
```
[myworkflow]
FirstState=pending
#<previous_objectstate1_identifier>[<next_objectstate1_identifier>]=<action1_title>
pending[validated]=Validate
pending[refused]=Refuse
refused[pending]=Submit
validated[published]=Publish
published[archived]=Archive
```

**See extension/oweditorial/oweditorial.ini for more customization**

##Set eZPublish workflows
1. Create a new workflow named "Editorial init" in your favorite workflow group via "Setup > Workflows"

2. In this workflow, add an "Editorial init" event and save

3. If you want to enable notifications, create a workflow named "Editorial notifications" containing a "Editorial notifications" event.

4. Create a new workflow named "Multiplexer after publish"

5. In this workflow, add a "Multiplexer" event

6. In this multiplexer, enable "Editorial init" for content classes concerned by the editorial workflow.

7. You can add a multiplexer to set notifications too.

8. Link the "Multiplexer after publish" with the trigger "after/publish" via "Setup > Triggers"

##Set user rights
Set user rights with object states constraints. For example, anonymous users can only read objects with "published" states.
Validators users can only set "validated" or "refused" state on objects with "pending" state.

##Set dashboard settings
The editorial dashboard ("Editorial" tab) shows all editorial content sorted by state.
You can select displayed classes, and ignored states, in ini file :
```
[dashboard_myworkflow]
IgnoreState[]
IgnoreState[]=none
Classes[]
Classes[]=article
```
This settings will only display articles in editorial dashboard. All objects with "none" state will not be displayed. You can enable all classes with an empty Classes array.

#Usage
States and actions buttons will be displayed in full view of objects.

You can directly switch to a state with "My Workflow" tab in full view.

The "Editorial" tab in admin interface will display all your editorial content, sorted by state. You can customize this view in oweditorial.ini file, in "Dashboard" section.

#Troubleshooting

###No class 'editorialFunctionCollection' available
Try to regenerate autoloads

###No state or actions are displayed
Have you correctly setted your workflows and triggers ? Try to set manually an object state and clear view cache.
