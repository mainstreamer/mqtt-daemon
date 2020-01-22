<?php
namespace App\Repository;


class RecordRepository
{
    const TABLE = 'record';
    const FIELDS = ['id','temperature','humidity','pressure','co2','created'];

    public function getInsertQuery(): string
    {
        return 'INSERT INTO '.self::TABLE.' ('.self::FIELDS[1].', '.self::FIELDS[2].', '.self::FIELDS[3].', '.self::FIELDS[4].', '.self::FIELDS[5].') VALUES (?,?,?,?,?)';
        ////$query = 'INSERT INTO record (temperature, humidity, pressure, co2, created) VALUES (?,?,?,?,?);';
    }
    //$query = 'CREATE TABLE record (id INTEGER AUTO_INCREMENT NOT NULL, temperature FLOAT DEFAULT NULL, humidity FLOAT DEFAULT NULL, pressure  INTEGER DEFAULT NULL, co2 INTEGER DEFAULT NULL, created DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB';
}