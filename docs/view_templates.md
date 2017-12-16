View Templates
==============

Syntax
------

````
template   = html | "<?php" statement "?>" | "<?=" expression "?>" .
statement  = "foreach" "(" expression "as" variable ")" ":"
           | "endforeach"
           | "if" "(" expression ")" ":"
           | "elseif" "(" expression ")" ":"
           | "else" ":"
           | "endif" .
expression = string
           | variable { "->" identifier [ "(" [ expression { "," expression} ] ")" ]} .
````
