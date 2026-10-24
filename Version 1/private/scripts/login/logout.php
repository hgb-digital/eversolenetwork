<?php

function logout($dat = null) {
    session_unset();
    session_destroy();

    return true;
}