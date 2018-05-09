<?php    
    header('Access-Control-Allow-Origin: *'); 
    header("Content-type:text/html; charset=utf-8");
    require_once('./vendor/autoload.php');

    use app\server\models\Animal;
    use app\server\models\ImagemAnimal;
    use app\server\models\Associado;
    use app\server\models\Adocao;
    use app\server\models\Mensagem;
    use app\server\models\Denuncia;
    use app\server\controllers\Router;
    use app\server\controllers\Auth;
    use app\server\controllers\Upload;
    Router::dev();

    //TEMPLATE Principal
    Router::get('/', function() {
        Router::View("./app/client/index.html");
    });
    
    //End Points Animais
        Router::get('/animais', function() {

            Router::validateJwt();//Rota protegida por JWT

            $animal = new Animal();
            Router::Json( $animal->all() );
        });

        Router::get('/animais/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $animal = new Animal();
            Router::Json( $animal->find( $params->id ) );
        });

        Router::post('/animais', function($dados) {
            Router::validateJwt();//Rota protegida por JWT
            $animal = new Animal();

            if( Upload::move("./app/client/assets/imagens/animais", array(".jpg",".jpeg",".png")) == true and $animal->save( Upload::getName(), $_POST ) )
                Router::Json( 200 );
            else 
                Router::Err( 400 );
        });

        Router::put('/animais', function() {
            Router::validateJwt();//Rota protegida por JWT
            $animal = new Animal();
            
            if( $animal->update( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::delete('/animais/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $animal = new Animal();

            if( $animal->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points Animais

    //End Points Imagens_animais
        Router::post('/imagens_animais', function() {
            Router::validateJwt();//Rota protegida por JWT
            $imgAnimal = new ImagemAnimal();

            if( Upload::move("./app/client/assets/imagens/animais", array(".jpg",".jpeg",".png")) == true and $imgAnimal->save( Upload::getName(), $_POST["id_animal"] ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
        Router::delete('/imagens_animais/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $imgAnimal = new ImagemAnimal();

            if( $imgAnimal->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points Imagens_animais

    //End Points Associados
        Router::get('/associados', function() {
            Router::validateJwt();//Rota protegida por JWT
            $associado = new Associado();
            Router::Json( $associado->all() );
        });

        Router::get('/associados/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $associado = new Associado();
            Router::Json( $associado->find( $params->id ) );
        });

        Router::post('/associados', function() {
            Router::validateJwt();//Rota protegida por JWT
            $associado = new Associado();

            if( $associado->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Err( 400 );
        });

        Router::put('/associados', function() {
            Router::validateJwt();//Rota protegida por JWT
            $associado = new Associado();
            
            if( $associado->update( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::delete('/associados/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $associado = new Associado();

            if( $associado->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points Associados



    //End Points para Adoções
        Router::get('/adocoes', function() {
            Router::validateJwt();//Rota protegida por JWT
            $adocao = new Adocao();
            Router::Json( $adocao->all() );
        });

        Router::get('/adocoes/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $adocao = new Adocao();
            Router::Json( $adocao->find( $params->id ) );
        });

        Router::get('/adocoes/associado/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $adocao = new Adocao();
            Router::Json( $adocao->findByAssociado( $params->id ) );
        });

        Router::post('/adocoes', function() {
            Router::validateJwt();//Rota protegida por JWT
            $adocao = new Adocao();

            if( $adocao->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Err( 400 );
        });

        Router::put('/adocoes', function() {
            Router::validateJwt();//Rota protegida por JWT
            $adocao = new Adocao();
            
            if( $adocao->update( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::delete('/adocoes/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $adocao = new Adocao();

            if( $adocao->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points para Adoções


    //End Points para Mensagens
        Router::get('/mensagens', function() {
            Router::validateJwt();//Rota protegida por JWT
            $mensagem = new Mensagem();
            Router::Json( $mensagem->all() );
        });

        Router::get('/mensagens/adocao/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $mensagem = new Mensagem();
            Router::Json( $mensagem->findByAdocao( $params->id ) );
        });

        Router::post('/mensagens', function() {
            Router::validateJwt();//Rota protegida por JWT
            $mensagem = new Mensagem();

            if( $mensagem->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Err( 400 );
        });
    //End Points para Mensagens


    //End Points para Denuncias
        Router::get('/denuncias', function() {
            Router::validateJwt();//Rota protegida por JWT
            $denuncia = new Denuncia();
            Router::Json( $denuncia->all() );
        });

        Router::get('/denuncias/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
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
            Router::validateJwt();//Rota protegida por JWT
            $denuncia = new Denuncia();

            if( $denuncia->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points para Denuncias


    //End Point para Filtro de animais
        Router::get('/filtro/{cor}/{idademin}/{idademax}/{sexo}', function($params) {
            $animal = new Animal();
            Router::Json( $animal->filtro( $params ) );
        });
    //End Point para Filtro de animais


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
    
    //End Point Login para Admin
        Router::post('/adminauthentication', function() {
            $dados = Router::getJson();

            $logar = new Auth($dados->login, $dados->password, 2); //2-> é o tipo de usuario administrador

            if ($logar->logar() !== 'err')
                Router::Json($logar->logar());
            else
                Router::Json(401);
        });
    //End Point Login para Admin


    Router::notFound("./app/client/notFound.html");
