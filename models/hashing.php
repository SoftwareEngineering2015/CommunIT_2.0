<?php
/**
 * We just want to hash our password using the current DEFAULT algorithm.
 * This is presently BCRYPT, and will produce a 60 character result.
 *
 * Beware that DEFAULT may change over time, so you would want to prepare
 * By allowing your storage to expand past 60 characters (255 would be good)
 */
$hash = (password_hash("password", PASSWORD_DEFAULT));
echo $hash;

// See the password_hash() example to see where this came from.
//$hash = '$2y$10$NRnc6wcRPuuCkrnq0FV4oeOPChMhAdE3ap9tKO6.Hngkrsf7WOB4u';

if (password_verify('password', $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}

?>