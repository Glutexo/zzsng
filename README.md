# Introduction #

“zzsng” is a simple tool for vocabulary revising (the flashcard method) when learning a foreign language. It also serves as a simple vocabulary database with an ability to categorize the vocabulary into groups (lessons) and to filter them by language.

It is written in [PHP](http://www.php.net) and works with either a [MySQL](http://www.mysql.com) or [PostgreSQL](http://www.postgresql.org) database. For this application to work you need only a working [PHP](http://www.php.net) installation with a DB connection, either on your computer or on your remote hosting service. No special configuration or libraries needed.

There is a working demo at [http://zzsng.herokuapp.com/](http://zzsng.herokuapp.com/).

# Installation #

You can run this tool on your local webserver that supports running [PHP](http://www.php.net) applications (e. g. [Apache](http://projects.apache.org/projects/http_server.html)). In addition to that you need a database server ([MySQL](http://www.mysql.com) and [PostgreSQL](http://www.postgresql.org] are supported) that can be accessed by the [PHP](http://www.php.net) scripts.

After cloning the application somewhere to your document root for it to be accessible by the webserver, you need to create a database for it. After that enter your DB configuration to the _include/db_config.php_ file. You need to fill in a hostname (e. g. localhost), port, username and password to access your database server and a name of your DB. If you use [PostgreSQL](http://www.postgresql.org], set the _TYPE_ constant to _'pgsql'_; if you use [MySQL](http://www.mysql.com), set it to _'mysql'.

If your DB user has sufficent rights to create new database objects, you can run the automatic DB setup by accessing the application via an URL like this:

    http://localhost/zzsng/index.php?section=setup

This will create the tables, columns, indices, sequences etc. needed for the application to run.

If you don’t want to give your user the rights to manage your database object, you can import the structure manually using the tools you prefere. You can find the SQL structure scripts in the _sql/structure.mysql.sql_ and _sql/structure.pgsql.sql_ depending on your DB type.

After that you can run the app simply by accessing the _index.php_ from your web-browser.

It is a good idea to create a first language then: Pick _Languages_ from the bottom menu and create one you want to learn. Without having at least one language entered, you wouldn’t be able to create any lessons or fill in a vocabulary.

# Features #

This tool is meant for people learning one or more foreign languages. After you enter your vocabulary into the database, you can let the program examine your knowledge. It uses the most well-known method of flashcards or “two pockets”.

For example: You are using a text-book and you want to learn all the words from a certain lesson. So you create a lesson and put in all the words from it, then you let the program examine you: It will show you one foreign word at time and you’ll think about it and say/write down the meaning. After that, you let the computer to show you the real meaning and you tell it whether you knew it or not. After all the words from a lesson will be presented, you’ll get the words you didn’t know again. This process repeats until you know all the words.

## Languages ##

The application is built for learning more than one language, even though it can be perfectly used for learning just one. Because of this there is an agenda for managing languages (pick _Languages_ from the bottom menu). Without having at least one language filled in, there is no way how to create lessons or insert new words.

One language can be marked as default. When creating a new lesson, the default language is preselected.

## Lessons, terms ##

Each piece of the vocabulary has to belong to a lesson. To manage lessons, pick _Lessons_ from the bottom menu. There you can create new lessons and rename or delete the existing ones. Once a lesson is created, it is possible to put new words in it by clicking the _Terms_ button at the end of each table row.

The lesson list can be filtered to show only those belonging to a chosen language. To filter the list, pick a language from the combobox in the table head.

After clicking the _Terms_ button it is possible to add new terms to the lesson and rename/delete the existing ones. The terms can be sorted either by the timestamp of creation (default) or alphabeticaly. The sorting methods can be switched using the buttons in the table head.

Each term must have its original and translated version filled in. There are two more fields: metadata and comment. These two fields are optional. Metadata is a piece of short additional information that accompanies the word, but which should not be presented right away with it when examining. It is a place where  information like genders, classes, genitive endings, pronunciation or valency go. During the exam, you always see only the term without its metadata, but after revealing the translation, the medatata are present too. The same applies for the comments only with the difference that it can contain longer, multiline comments like e. g. examples, sample sentences, phrases etc.

## Examination ##

The default page shown when the application is opened serves to start an exam. It is also accessible by the _Exam_ bottom menu item. At this page there is a picker to choose one or more lessons.

There is also an option to choose whether the terms from the lesson(s) shall be presented in random order (default) or ordered by the creation timestamp. The latter can be useful when the lesson does not contain just bare words, but for example whole sentences from a textbook exercise.

After hitting the _Start exam_ button, a first word from a chosen lesson is shown to the user, without its metadata, translation and comment. This is the time for thinking about what the translation of the word is, what it means, how is it pronounced etc. After thinking that out, writing that on a piece of paper or to a separate text file, it is possible to reveal all the info about the word: That means its translation, metadata and comment. When this information is checked, the buttons _I knew it_ and _I didn’t know it_ can be used to tell the program the state of our knowledge.

By default, the foreign language word is displayed and it’s translation stays hidden, until the “Reveal” button is clicked. This can be changed by checking the “base on translations” checkbox right below the lesson list. If checked, translations are displayed in the first place and the original term is shown, after using the “Reveal” button.

When the last word from the lesson is presented, a new round begins only with the terms that have been marked as _not known_. This cycle repeats until all the words are guessed correctly. After finnishing the last iteration, a message appears saying how many words were correct in the very first part of the exam, how many are they in total and a percentual representation of the success rate.

The program does not provide any mechanism to check up the user’s guess with the data in the database, because there is no way to automatically tell whether the user really knew it or not: The meaning of the word consist not only from its right spelling and for example even guessing a synonym can be still considered correct. So it’s up to the user himself to decide which words he  need to see once again and which is he already confident with.

## Import, bulk insertion ##

The two menu items _Import_ and _Bulk_ contain the functionality of importing CSV files containing a lesson or a part of it. The _Import_ item is for uploading real text files, on the other hand the _Bulk_ provides user with a textarea where the semicolon-separated data can be put in.

Using the _Import_ function it is only possible to import whole lessons. The input file has to be UTF-8 encoded plain text file without a BOM at the beginning. Each line contains one term with its metadata, translation and a comment. The properties are separated with a semicolon and they are not quoted in any way, even if they contain spaces. There is no way to put a semicolon to a term, its metadata, translation or comment. It is also not possible to insert comments with line-breaks into this pseudo-CSV file. LF and CRLF line-endings should be imported without problems, I am not sure with just CR though.

The file would look like this:

    Abend;(n) r;evening;Guten Abend. = Good evening.
    Fahrrad;(n) s;bicycle, bike
    neu, neue;(adj);new
    
The _Bulk_ option works similar, but the file is not uploaded but put into a textarea. With the _Bulk_ option it is also possible to import many words at once to an existing lesson. Unfortunately this is not possible with the file import functionality mentioned earlier.

The data in the textarea have to be in the same format as with the CSV file: Separated by semicolon with no quotation. No possibility to make a semicolon a part of any property, no way to provide a multiline comment.

If there is an error in the CSV file or the data in the textarea, the invalid lines are presented to the user as a part of an error message and nothing will be imported.

## Localization ##

English and Czech localization of the user interface is supported. The language can be switched by altering the _DEFAULT_LANGUAGE_ constant in the _index.php_ file. Use _'en'_ for English, _'cs'_ for Czech language.

# Blurb #

This application was never meant to be public and it has been written a long time ago. It doesn’t use any framework and my programming skills were pretty mediocre the time it was created. I am not planning to continue with the development, because it does everything I need and if I’d want to make it more usable or to add more features, I’d prefere to rewrite it completely, probably abandoning [PHP](http://www.php.net) and using [Ruby on Rails](http://rubyonrails.org) instead.

The last new features added only for the purpose of deployment to [Heroku](http://www.heroku.com) and publishing to [GitHub](https://github.com) were the English localization of the code, comments and the user interface and the support for [PostgreSQL database](http://www.postgresql.org).

But if anybody wants to fix some bugs or implement new features to this legacy version, feel free to submit your patches.

UPDATE: An exception has been made to the no-new-features statement. I needed an option to change order of terms in a lesson, so this option has been added.

UPDATE 2: It is now possible to enable “reverse” examination: Instead of the term being displayed in a foreign language and then revealed its Czech translation, the translation is presented. Revaling the missing data diplays the original term in the foreign language.

# New features – ideas #

* Tree lesson hierarchy, languages being the top level.
* Recoverable exam session.
* Undo after clicking marking a term as known/unknown.
* Direct term/translation/etc. editting from examination.
* Multi-user environment with public/private lessons.
* Statistics.  
