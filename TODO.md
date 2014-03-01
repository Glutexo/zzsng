# Todo list #

Even though this application is meant to be abandoned in a favor of a new version written from a scratch, I would still like to leave in a state where every feature works as intended. That means: No new features, but also no bugs or stupid security flaws.

1. Fix the language filtering in the lesson list: The first item of the language filtering select-box shall have the „no filter“ option at its top. When no filter is active, this one shall be selected.
2. Let the active user name displayed in the page header show the correct value even right after signing in. In the actual state, the name is changed upon the next request after signing in. The first page loaded after signing in displays the username that was active before.
3. Correct the typo “obtainet” in the error message telling that lesson (or terms?) list cannot be obtained.
4. Allow user to manage only those lessons that he has created: No CRUD action shall be performed on a lesson belonging to a different owner. The same applies to the terms belonging those lessons.
5. Mark Glutexo as a superuser: Superusers can manage languages. Basic users can only add new languages, but they are not allowed to rename it or delete it. A message shall be displayed to basic users explaining why some features are not available.