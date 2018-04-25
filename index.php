<?php    
    header('Access-Control-Allow-Origin: *'); 
    header("Content-type:text/html; charset=utf-8");
    require_once('./vendor/autoload.php');

    use app\server\models\Animal;
    use app\server\models\Associado;
    use app\server\controllers\Router;
    use app\server\controllers\Auth;
    Router::dev();

    //TEMPLATE Principal
    Router::get('/', function() {
        Router::View("./app/client/index.html");
    });
    
    //End Points Animais
        Router::get('/animais', function() {
            $animal = new Animal();
            Router::Json( $animal->all() );
        });

        Router::get('/animais/{id}', function($params) {
            $animal = new Animal();
            Router::Json( $animal->find( $params->id ) );
        });

        Router::post('/animais', function($dados) {
            $animal = new Animal();

            if( $animal->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Err( 400 );
        });

        Router::put('/animais', function() {
            $animal = new Animal();
            
            if( $animal->update( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::delete('/animais/{id}', function($params) {
            $animal = new Animal();

            if( $animal->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points Animais

    //End Points Associados
        Router::get('/associados', function() {
            $associado = new Associado();
            Router::Json( $associado->all() );
        });

        Router::get('/associados/{id}', function($params) {
            $associado = new Associado();
            Router::Json( $associado->find( $params->id ) );
        });

        Router::post('/associados', function() {
            $associado = new Associado();

            if( $associado->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Err( 400 );
        });

        Router::put('/associados', function() {
            $associado = new Associado();
            
            if( $associado->update( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::delete('/associados/{id}', function($params) {
            $associado = new Associado();

            if( $associado->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points Associados

    //End Point Login para Associados
        Router::post('/authentication', function() {
            $dados = Router::getJson();
            $logar = new Auth($dados->login, $dados->password, 1);
            if ($logar->logar() !== 'err')
                Router::Json($logar->logar());
            else
                Router::Json(401);
        });
    //End Point Login para Associados
    
    //End Point Login para Associados
        Router::post('/adminauthentication', function() {
            $dados = Router::getJson();

            $logar = new Auth($dados->login, $dados->password, 2); //2-> é o tipo de usuario administrador

            if ($logar->logar() !== 'err')
                Router::Json($logar->logar());
            else
                Router::Json(401);
        });
    //End Point Login para Associados


    Router::notFound("./app/client/notFound.html");
