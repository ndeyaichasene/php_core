<?php

namespace App\Core;
use PDO;
use PDOException;
use PDOStatement;

class Database
{

    private function __construct(){}

    private static function getInstance(): PDO | null
    {
        try {
            $instance = null;
            $dsn = "pgsql:host=localhost;dbname=nom_database";
            $instance = new PDO($dsn, "user", "password");
            $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $instance;
        } catch (PDOException $e) {
            error_log("Connexion PostgreSQL échouée : " . $e->getMessage());
            return null;
        }
    }
 

    public static function query(string $sql, bool $single = true): mixed
    {
        $query = self::getInstance()->query($sql);
        return $single ? $query->fetch(PDO::FETCH_OBJ) : $query->fetchAll(PDO::FETCH_OBJ);
    }

    private static function prepare(string $sql, array $datas): PDOStatement
    {
        $prepare = Database::getInstance()->prepare($sql);
        $prepare->execute($datas);
        return $prepare;
    }

    public static function executeQuery(string $sql, array $datas, bool $single = true): mixed
    {
        $statement = self::prepare($sql, $datas);
        return $single ? $statement->fetch(PDO::FETCH_OBJ) : $statement->fetchAll(PDO::FETCH_OBJ);
    }

   

    public static function executeUpdate(string $sql, array $datas): int|string
    {
        $statement = self::prepare($sql, $datas);
        return (str_starts_with(strtoupper(trim($sql)), 'INSERT')) ? self::getInstance()->lastInsertId() : $statement->rowCount();
    }

    public static function beginTransaction(): void
    {
        self::beginTransaction();
    }

    public static function commit(): void
    {
        self::commit();
    }

    public static function rollback(): void
    {
        self::rollBack();
    }
     public static function getAllData(string $tableName): array
    {
        $sql = "SELECT * FROM $tableName";
        return self::query($sql, false);
    }

    
}