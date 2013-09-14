Cricket
=======

Cricket is a very small proof of concept for translating PHP 5.3 closures
to Doctrine DQL snippets.

For example, the following:

    function ($u) use ($user) {
        return $u == $user;
    }

Is converted into:

    "u = :user"

Check out /spec/Cricket/CricketBundle/Convertor/ClosureConvertorSpec.php
for current functionality.

Background
----------

I use the built-in tokenizer: tokens_get_all().

I built my own parser (Vaughan Pratt's top down operator precedence, like
Douglas Crockford used on JSLint) because I want to parse a specific
subset of PHP.

I added some basic magic to PHP's reflection, because you can't just ask
for the body of a function.

