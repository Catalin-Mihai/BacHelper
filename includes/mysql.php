<?php 
    class MySQL
    {   
        public static $ms_hMySQL;
        public static function Connect()
        {
            self::$ms_hMySQL = new mysqli("localhost", "root", "", "bachelper");
            /* check connection */
            if (self::$ms_hMySQL->connect_errno) {
                printf("Connect failed: %s\n", self::$ms_hMySQL->connect_errno);
                exit();
            }
            else 
            {
               if (!self::$ms_hMySQL->set_charset("utf8")) {
                    printf("Error loading character set utf8: %s\n", self::$ms_hMySQL->error);
                    exit();
                }
            }
        }
        public static function CloseConnection()
        {
            //mysqli_close(self::$ms_hMySQL);
            self::$ms_hMySQL->close();
        }
        public static function GetHandle()
        {
            return self::$ms_hMySQL;
        }
    }
?>