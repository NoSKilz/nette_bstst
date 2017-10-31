<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Authenticator
 *
 * @author NoSkilz
 */
use Nette\Security as NS;
class Authenticator implements NS\IAuthenticator
{
    public $database;
    function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
    function authenticate(array $credentials)
    {
        list($username,$password) = $credentials;
        $row = $this->database->table('user')
            ->where('user_name', $username)->fetch();
        if(!$row)
        {
            throw new NS\AuthenticationException('Uživatel nebyl nalezen.');
        }
        if(!NS\Passwords::verify($password, $row->password))
        {
            throw new NS\AuthenticationException('Zadali jste špatné heslo.');
        }
        return new NS\Identity($row->user_id,$row->role,['username' => $row->user_name]);
    }
}