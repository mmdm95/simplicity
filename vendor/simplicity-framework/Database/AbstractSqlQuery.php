<?php

namespace Sim\Database;


abstract class AbstractSqlQuery
{
    protected $keywords = [
        'ADD', 'ADD CONSTRAINT', 'ALTER', 'ALTER COLUMN', 'ALTER TABLE', 'ALL', 'AND', 'ANY', 'AS', 'ASC',
        'BACKUP DATABASE', 'BETWEEN',
        'CASE', 'CHECK', 'COLUMN', 'CONSTRAINT', 'CREATE', 'CREATE DATABASE', 'CREATE INDEX', 'CREATE OR REPLACE VIEW',
        'CREATE TABLE', 'CREATE PROCEDURE', 'CREATE UNIQUE INDEX', 'CREATE VIEW',
        'DATABASE', 'DEFAULT', 'DELETE', 'DESC', 'DISTINCT', 'DROP', 'DROP COLUMN', 'DROP CONSTRAINT', 'DROP DATABASE',
        'DROP DEFAULT', 'DROP INDEX', 'DROP TABLE', 'DROP VIEW',
        'EXEC', 'EXISTS',
        'FOREIGN KEY', 'FROM', 'FULL OUTER JOIN',
        'GROUP BY',
        'HAVING',
        'IN', 'INDEX', 'INNER JOIN', 'INSERT INTO', 'INSERT INTO SELECT', 'IS NULL', 'IS NOT NULL',
        'JOIN',
        'LEFT JOIN', 'LIKE', 'LIMIT',
        'NOT', 'NOT NULL',
        'OR', 'ORDER BY', 'OUTER JOIN',
        'PRIMARY KEY', 'PROCEDURE',
        'RIGHT JOIN', 'ROWNUM',
        'SELECT', 'SELECT DISTINCT', 'SELECT INTO', 'SELECT TOP', 'SET',
        'TABLE', 'TOP', 'TRUNCATE TABLE',
        'UNION', 'UNION ALL', 'UNIQUE', 'UPDATE',
        'VALUES', 'VIEW',
        'WHERE',
    ];

    protected $aggregate_functions = [
        'AVG', 'COUNT', 'MAX', 'MIN', 'SUM',
    ];

    protected $scalar_functions = [
        'UCASE', 'LCASE', 'MID', 'LEN', 'ROUND', 'NOW', 'FORMAT',
    ];
}