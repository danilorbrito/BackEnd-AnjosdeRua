<?php    
    header('Access-Control-Allow-Origin: *'); 
    header("Content-type:text/html; charset=utf-8");
    require_once('./vendor/autoload.php');

    use app\server\models\Animal;
    use app\server\models\Associado;
    use app\server\models\Adocao;
    use app\server\models\Mensagem;
    use app\server\models\Denuncia;
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



    //End Points para Adoções
        Router::get('/adocoes', function() {
            $adocao = new Adocao();
            Router::Json( $adocao->all() );
        });

        Router::get('/adocoes/{id}', function($params) {
            $adocao = new Adocao();
            Router::Json( $adocao->find( $params->id ) );
        });

        Router::get('/adocoes/associado/{id}', function($params) {
            $adocao = new Adocao();
            Router::Json( $adocao->findByAssociado( $params->id ) );
        });

        Router::post('/adocoes', function() {
            $adocao = new Adocao();

            if( $adocao->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Err( 400 );
        });

        Router::put('/adocoes', function() {
            $adocao = new Adocao();
            
            if( $adocao->update( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::delete('/adocoes/{id}', function($params) {
            $adocao = new Adocao();

            if( $adocao->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points para Adoções


    //End Points para Mensagens
        Router::get('/mensagens', function() {
            $mensagem = new Mensagem();
            Router::Json( $mensagem->all() );
        });

        Router::get('/mensagens/adocao/{id}', function($params) {
            $mensagem = new Mensagem();
            Router::Json( $mensagem->findByAdocao( $params->id ) );
        });

        Router::post('/mensagens', function() {
            $mensagem = new Mensagem();

            if( $mensagem->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Err( 400 );
        });
    //End Points para Mensagens


    //End Points para Denuncias
        Router::get('/denuncias', function() {
            $denuncia = new Denuncia();
            Router::Json( $denuncia->all() );
        });

        Router::get('/denuncias/{id}', function($params) {
            $denuncia = new Denuncia();
            Router::Json( $denuncia->find( $params->id ) );
        });

        Router::post('/denuncias', function() {
            $denuncia = new Denuncia();

            if( $denuncia->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Err( 400 );
        });

        Router::delete('/denuncias/{id}', function($params) {
            $denuncia = new Denuncia();

            if( $denuncia->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points para Denuncias


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
