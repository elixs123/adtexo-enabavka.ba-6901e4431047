<?php

if (! function_exists('userHasRole')) {
    /**
     * @param mixed $role
     * @return bool
     */
    function userHasRole($role) {
        return auth()->check() ? auth()->user()->hasRole($role) : false;
    }
}

if (! function_exists('userIsAdmin')) {
    /**
     * @return bool
     */
    function userIsAdmin() {
        return auth()->check() ? auth()->user()->isAdmin() : false;
    }
}

if (! function_exists('userIsSalesman')) {
    /**
     * @return bool
     */
    function userIsSalesman() {
        return auth()->check() ? auth()->user()->isSalesman() : false;
    }
}

if (! function_exists('userIsClient')) {
    /**
     * @return bool
     */
    function userIsClient() {
        return auth()->check() ? (auth()->user()->isClient() || auth()->user()->isSalesAgent()) : false;
    }
}

if (! function_exists('userIsSupervisor')) {
    /**
     * @return bool
     */
    function userIsSupervisor() {
        return auth()->check() ? auth()->user()->isSupervisor() : false;
    }
}

if (! function_exists('userIsWarehouse')) {
    /**
     * @return bool
     */
    function userIsWarehouse() {
        return auth()->check() ? auth()->user()->isWarehouse() : false;
    }
}

if (! function_exists('userIsEditor')) {
    /**
     * @return bool
     */
    function userIsEditor() {
        return auth()->check() ? auth()->user()->isEditor() : false;
    }
}

if (! function_exists('userIsFocuser')) {
    /**
     * @return bool
     */
    function userIsFocuser() {
        return auth()->check() ? auth()->user()->isFocuser() : false;
    }
}

if (! function_exists('userIsSalesAgent')) {
    /**
     * @return bool
     */
    function userIsSalesAgent() {
        return auth()->check() ? auth()->user()->isSalesAgent() : false;
    }
}
