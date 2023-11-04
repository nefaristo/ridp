<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response; //SVSV
use Illuminate\Validation\ValidationException; //SVSV
use App\SVLibs\utils; //SVSV
use Illuminate\Support\Facades\DB; //SVSV

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response 
     */
    public function render($request, Exception $e)
    {    
        //return parent::render($request, $e);//SVSV ctrl
        //return \App\SVLibs\utils::viewStuff($request->all());
        $message="";
        switch($e){   
            case ($e instanceof \Illuminate\Session\TokenMismatchException):
                $message="Sessione scaduta. Riaggiornare la pagina prima di salvare.";
                break;
            case ($e instanceof \Illuminate\Database\QueryException)://SQL exceptions
            //case($e instanceof \Symfony\Component\Debug\Exception\FatalThrowableError):
                $sql = $e->getSql();
                $bindings = $e->getBindings();
                // Process the query's SQL and parameters and create the exact query
                foreach ($bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $bindings[$i] = "'$binding'";
                        }
                    }
                }
                $query = str_replace(array('%', '?'), array('%%', '%s'), $sql);
                $query = vsprintf($query, $bindings);
                $errorInfo = $e->errorInfo;
                $output = [
                    'sql'        => $query,
                    'message'    => isset($errorInfo[2]) ? $errorInfo[2] : '',
                    'sql_state'  => $errorInfo[0],
                    'code' => $errorInfo[1]
                ];                
                $state_err=$errorInfo[0].".".$errorInfo[1]; //SQLSTATE.error_code
                if(array_key_exists($state_err,trans("rdp.errors"))){
                    $message=trans("rdp.errors")[$state_err];                    
                }else{ // not mapped error - see https://dev.mysql.com/doc/refman/5.5/en/error-messages-server.html
                    $message="SQL ERROR - query: " .$query." | message: ". (isset($errorInfo[2]) ? $errorInfo[2] : '') . " | state.err:". $state_err;
                }
                break;  
            //case($e instanceof \Symfony\Component\Debug\Exception\FatalThrowableError):
            //    $message ="Fatal throwable error, see handler for details";
            //    break;
            case($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException):
                $message =$request->url() . " not found";
                break;
            case ($e instanceof ValidationException)://Laravel Validation Execption (reminder)
                break;
            default: //just to see the kind of exception if it is to be caught:
                //return response(get_class($e) ." error " . $e->getCode() . "\n " . $e->getMessage() . "\n\n in " . $e->getFile() . ", line ". $e->getLine());  
                
        };
        if ($message!=""){//managed here:    
            return response($message);
        }else{//managed by parent, ie validation errors:
            return parent::render($request, $e);//default
        }
        //DB::insert('insert into users (id, name) values (?, ?)', [1, 'Dayle']);<==HERE
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        return redirect()->guest('login');
    }
}
