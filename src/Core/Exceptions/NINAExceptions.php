<?php
namespace NINA\Core\Exceptions;
class NINAExceptions extends  \Exception
{
    private $response;
    public function __construct($message = "", $code = 0, Throwable $previous = null, $response = null)
    {
        $this->response = $response;
        parent::__construct($message, $code, $previous);
    }
    public function redirectTo500Page()
    {
        if ($this->response !== null) {
            $this->response->redirect("/error500.php");
        } else {
            header("Location: /error500.php");
            exit;
        }
    }
}