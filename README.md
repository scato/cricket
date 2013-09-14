Cricket
=======

Cricket is a very small proof of concept for translating PHP 5.3 closures
to Doctrine DQL snippets.

For example, the following:

    function ($u) use ($user) {
        return $u == $user;
    }

is converted into:

    "u = :user"

