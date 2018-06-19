<?php    
    header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Credentials: false");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
    header('Access-Control-Allow-Headers: Authorization, content-type');
    header("Content-type:text/html; charset=utf-8");
    require_once('./vendor/autoload.php');
	date_default_timezone_set('America/Sao_Paulo');

    use app\server\models\Animal;
    use app\server\models\ImagemAnimal;
    use app\server\models\Associado;
    use app\server\models\Adocao;
    use app\server\models\Mensagem;
    use app\server\models\Denuncia;
    use app\server\models\ImagemDenuncia;
    use app\server\models\AcaoPromovida;
    use app\server\models\ListaDeEspera;
    use app\server\controllers\Router;
    use app\server\controllers\Auth;
    use app\server\controllers\Upload;
	use app\server\controllers\ReSizeImage;
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

        Router::get('/animais/{nome}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $animal = new Animal();
            Router::Json( $animal->find( $params->nome ) );
        });

        Router::post('/animais', function() {
            Router::validateJwt();//Rota protegida por JWT
            $animal = new Animal();

            $resgistro = $animal->save(Router::getJson());
            if($resgistro)
                Router::Json($resgistro);
            else 
                Router::Json( 400 );
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
        Router::post('/imagens/animais', function() {
            Router::validateJwt();//Rota protegida por JWT
            $imgAnimal = new ImagemAnimal();

            if( Upload::move("./app/client/assets/imagens/animais", array(".jpg",".jpeg",".png")) == true and $imgAnimal->save( Upload::getName(), $_POST["id_animal"] ) )
            {    
                //os dois caminhos estão eguais pois a imagem redimensionado será salva no próprio arquivo
                //primeiro paramentro 100 para largura, segundo 0 para altura pois a imagem se ajustará automaticamente, e o ultimo 100 para qualidade sendo o máximo
                $resize = new ReSizeImage(  './app/client/assets/imagens/animais/'.Upload::getName(),
                                            './app/client/assets/imagens/animais/'.Upload::getName(),
                                            100, 0, 100);
                $resize->executa();
                
                Router::Json( 200 );
            }
            else 
                Router::Json( 400 );
        });

        Router::get('/imagens/animais/{id}', function($params) {
            $imgAnimal = new ImagemAnimal();
            Router::Json($imgAnimal->find($params->id));
        });

        Router::delete('/imagens/animais/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $imgAnimal = new ImagemAnimal();

            if( $imgAnimal->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points Imagens_animais

    //End Point para Filtro de animais
        Router::get('/filtro/{sexo}/{idademin}/{idademax}/{raca}/{cor}', function($params) {
            $animal = new Animal();
            Router::Json( $animal->filtro( $params ) );
        });
    //End Point para Filtro de animais

    

    //End Points para Denuncias
        Router::get('/denuncias', function() {
            Router::validateJwt();//Rota protegida por JWT
            $denuncia = new Denuncia();
            Router::Json($denuncia->all());
        });

        Router::get('/denuncias/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $denuncia = new Denuncia();
            Router::Json($denuncia->find($params->id));
        });

        Router::post('/denuncias', function() {
            $denuncia = new Denuncia();

            $resgistro = $denuncia->save(Router::getJson());
            if($resgistro)
                Router::Json($resgistro);
            else 
                Router::Json( 400 );
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

    //End Points Imagens_denuncias
        Router::post('/imagens/denuncias', function() {
            Router::validateJwt();//Rota protegida por JWT
            $imgDenuncia = new ImagemDenuncia();

            if( Upload::move("./app/client/assets/imagens/denunciadas", array(".jpg",".jpeg",".png")) == true and $imgDenuncia->save( Upload::getName(), $_POST["id_denuncia"] ) )
            {    
                //os dois caminhos estão eguais pois a imagem redimensionado será salva no próprio arquivo
                //primeiro paramentro 100 para largura, segundo 0 para altura pois a imagem se ajustará automaticamente, e o ultimo 100 para qualidade sendo o máximo
                $resize = new ReSizeImage(  './app/client/assets/imagens/denunciadas/'.Upload::getName(),
                                            './app/client/assets/imagens/denunciadas/'.Upload::getName(),
                                            100, 0, 100);
                $resize->executa();
                
                Router::Json( 200 );
            }
            else 
                Router::Json( 400 );
        });

        Router::get('/imagens/denuncias', function() {
            Router::validateJwt();//Rota protegida por JWT
            $imgDenuncia = new ImagemDenuncia();
            Router::Json( $imgDenuncia->all() );
        });

        Router::delete('/imagens/denuncias/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $imgDenuncia = new ImagemDenuncia();

            if( $imgDenuncia->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points Imagens_denuncias




    //End Points Associados
        Router::get('/associados', function() {
            Router::validateJwt();//Rota protegida por JWT
            $associado = new Associado();
            Router::Json( $associado->all() );
        });

        Router::get('/associados/{nome}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $associado = new Associado();
            Router::Json( $associado->find( $params->nome ) );
        });

        Router::post('/associados', function() {
            Router::validateJwt();//Rota protegida por JWT
            $associado = new Associado();

            if( $associado->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
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
                Router::Json( 400 );
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

        Router::get('/mensagens/visualizar/admin/{id_adocao}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $mensagem = new Mensagem();
            Router::Json( $mensagem->messagesForAdmin($params->id_adocao) );
        });

        Router::get('/mensagens/visualizar/associado/{id_associado}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $mensagem = new Mensagem();
            Router::Json( $mensagem->messagesForAssociado( $params->id_associado ) );
        });

        Router::post('/mensagens', function() {
            Router::validateJwt();//Rota protegida por JWT
            $mensagem = new Mensagem();

            if( $mensagem->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::put('/mensagens/visualizadas', function() {
            Router::validateJwt();//Rota protegida por JWT
            $mensagem = new Mensagem();

            if( $mensagem->modifyStatus( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points para Mensagens

    //End Points para Ações Promovidas
        Router::get('/acoespromovidas', function() {
            //Router::validateJwt();//Rota protegida por JWT
            $acProm = new AcaoPromovida();
            Router::Json( $acProm->all() );
        });

        Router::get('/acoespromovidas/{id}', function($params) {
            //Router::validateJwt();//Rota protegida por JWT
            $acProm = new AcaoPromovida();
            Router::Json( $acProm->find($params->id) );
        });

        Router::post('/acoespromovidas', function() {
            Router::validateJwt();//Rota protegida por JWT
            $acProm = new AcaoPromovida();
            if( $acProm->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::put('/acoespromovidas', function() {
            Router::validateJwt();//Rota protegida por JWT
            $acProm = new AcaoPromovida();
            if( $acProm->update( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::delete('/acoespromovidas/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $acProm = new AcaoPromovida();
            if( $acProm->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points para Ações Promovidas
	
	//End Points para Lista de Espera
        Router::get('/listadeespera', function() {
            Router::validateJwt();//Rota protegida por JWT
            $lista = new ListaDeEspera();
            Router::Json( $lista->all() );
        });

        Router::get('/listadeespera/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $lista = new ListaDeEspera();
            Router::Json( $lista->find($params->id) );
        });

        Router::post('/listadeespera', function() {
            $lista = new ListaDeEspera();
            if( $lista->save( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::put('/listadeespera', function() {
            Router::validateJwt();//Rota protegida por JWT
            $lista = new ListaDeEspera();
            if( $lista->update( Router::getJson() ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });

        Router::delete('/listadeespera/{id}', function($params) {
            Router::validateJwt();//Rota protegida por JWT
            $lista = new ListaDeEspera();
            if( $lista->trash( $params->id ) )
                Router::Json( 200 );
            else 
                Router::Json( 400 );
        });
    //End Points para Lista de Espera

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

