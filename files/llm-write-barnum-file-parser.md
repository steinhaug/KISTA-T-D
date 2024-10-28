Write a PHP script that opens a directory (./data/) and processes all the files in it.

When processing the directory, assign the filename to $filename.

First we want to look at the $filename itself, as it has a syntax that tells us which LLM we are analyzing.
The filename should start with the word barnum, then a hyphen then the name of the LLM before a new hyphen.
The rest of the filename is important as this will tell us the category for the file.

So from the filename we populate two variables: $llm and $category

Lets process the file.
What we are looking for are lines that have the following data, 4 | characters.
We want to process theese lines, and the way we should do that is the following:

| (required) $text | (optional) $sex | (required) $score |

A successfull parsed line like this should call the function add_to_db($llm, $category, $sex, $score, $text);

