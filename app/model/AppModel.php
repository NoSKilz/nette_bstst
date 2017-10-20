<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppModel
 *
 * @author NoSkilz
 */
namespace App\Model;
use Nette;
use Nette\Security\Passwords;
class AppModel
{
    use Nette\SmartObject;
    private $database,$user;
    public function __construct(Nette\Database\Context $database,Nette\Security\User $user)
    {
        $this->database = $database;
        $this->user = $user;
    }
    public function getBestGames()
    {
        return $this->database->table('product')->select('product_id,product_name,platform_name,price')->order('sold DESC')->limit(20);
    }
    public function getNewGames($n)
    {
        return $this->database->table('product')->select('product_id,product_name,platform_name,price,picture,description')->order('uploaded DESC')->limit($n);
    }
    public function getPlatforms()
    {
        return $this->database->table('platform')->select('platform_name');
    }
    public function getGenres()
    {
        return $this->database->table('genre')->select('genre_name');
    }
    public function register($values)
    {
        $password=Passwords::hash($values->rpassword,['cost' => 12]);
        try
        {
        $registered=$this->database->table('user')->insert([
            'user_name'=>$values->rusername,
            'password'=>$password,
            'joined'=>date('Y-m-d H:i:s'),
            'user_email'=>$values->email,
            'admin'=>0,
            'role'=>'user'
            ]);
        }
        catch (Nette\Database\UniqueConstraintViolationException $e)
        {
            if(strpos($e,'user_name'))
            {
                return 'user_name';
            }
            else
            {
                return 'user_email';
            }
        }
            return 'succes';
    }
    public function login($username,$password) 
    {
        $authenticator=new \Authenticator($this->database);
        try
        {
            $this->user->setAuthenticator($authenticator);
            $this->user->login($username,$password);
            return 'success';
        }
        catch (Nette\Security\AuthenticationException $e) 
        {
            return 'error';
        }
    }
}