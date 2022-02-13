# ql-calendar
A calendar plugin for WordPress that automatically creates a calendar based on post tags

## Usage
Create a site/post where your calendar should be displayed, and add `[qlcal]`.
Then, on the site/post you want to add to the calendar, add a tag in the following format:

- `<day>/<month>`  
Example: `01/02` -> February 1st of every year

- `<day>/<month>:<weekday>`  
Example: `01/02` -> The first monday after February 1st (or Feb 1st if it's a monday)

- `<number>/<weekday>/<month>`  
Example: `03/mon/02` -> The 3. monday in february

- `<number>/week/<month>`  
Example: `02/week/02` -> The 2. week in february

- `last/<weekday>/<month>`  
Example: `last/mon/02` -> Last monday in february

- `last/week/<month>`  
Example: `last/week/02` -> Last (full) week in february

(Weeks start on Monday.)

The plugin then automatically creates a calendar for the next 365 days based on the posts with those tags.

## Demo
https://queer-lexikon.net/queerer-kalender/