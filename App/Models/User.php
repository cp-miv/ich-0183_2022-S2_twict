<?php

namespace App\Models;

class User extends \Core\Model
{
    public static function getAll()
    {
        $db = static::getDB();

        $models = $db
                    ->query('SELECT * FROM users WHERE deletedAt IS NULL;')
                    ->fetchAll();

        return $models;
    }

    public static function find(int $id)
    {
        $db = static::getDB();

        $model = $db
            ->query(<<< SQL
                    SELECT `idUser`, `firstname`, `lastname`, `mailAddress`, `password`, `createdAt`, `updatedAt`, `deletedAt` FROM `Users`
                    WHERE `idUser` = {$id}
                    LIMIT 1;
                SQL)
            ->fetch();

        return $model;
    }

    public static function add($model) : bool
    {
        $db = static::getDB();
        $success = $db
                    ->prepare(<<< SQL
                        INSERT INTO `Users`
                            (`firstname`, `lastname`, `mailAddress`, `password`)
                        VALUES
                            ('{$model['firstname']}', '{$model['lastname']}', '{$model['mailAddress']}', '{$model['password']}');
                        SQL)
                    ->execute();

        return $success;
    }

    public static function update($model)
    {
        $db = static::getDB();

        $success = $db
            ->prepare(<<< SQL
                UPDATE `Users` SET
                    `firstname` = '{$model['firstname']}',
                    `lastname` = '{$model['lastname']}',
                    `mailAddress` = '{$model['mailAddress']}',
                    `password` = '{$model['password']}',
                    `updatedAt` = CURRENT_TIMESTAMP
                WHERE `idUser` = {$model['idUser']}
                LIMIT 1;
                SQL)
            ->execute();

        return $success;
    }
}