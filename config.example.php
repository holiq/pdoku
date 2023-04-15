<?php

enum Config: string
{
    case connection = 'mysql'; // mysql = nysql|mariadb, pgsql = postgresql

    case host = 'localhost';

    case port = '3306'; // 3306 = mysql|mariadb, 5432 = postgresql

    case username = 'root';

    case password = '';

    case database = 'pdoku';
}
