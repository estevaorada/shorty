<?php
require_once __DIR__ . '/../config.php';

class Database
{
    private static $dbNome = DB_NAME; 
    private static $dbHost = DB_HOST;
    private static $dbUsuario = DB_USER;
    private static $dbSenha = DB_PASS;

    private static $cont = null;

    // Impede instanciação da classe
    private function __construct()
    {
        throw new Exception('A instanciação da classe Banco não é permitida!');
    }

    /**
     * Estabelece uma conexão com o banco de dados.
     * @return PDO|null Retorna a conexão PDO ou null em caso de falha.
     */
    public static function conectar()
    {
        if (null === self::$cont) {
            try {
                $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', self::$dbHost, self::$dbNome);
                self::$cont = new PDO($dsn, self::$dbUsuario, self::$dbSenha, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $exception) {
                // Logar o erro em vez de exibi-lo diretamente em produção
                error_log($exception->getMessage());
                return null; // Ou lançar uma exceção personalizada
            }
        }
        return self::$cont;
    }

    /**
     * Desconecta explicitamente do banco de dados.
     * Geralmente não é necessário em aplicações web.
     */
    public static function desconectar()
    {
        self::$cont = null;
    }
}

?>