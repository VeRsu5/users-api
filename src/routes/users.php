<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//Get all users request
$app->get('/api/users', function(Request $request, Response $response ){
    $sql = "SELECT * FROM user";

    try {
        //Get db object
        $db = new db();
        //Connect
        $db = $db->connect();
        //Querying
        $stmt = $db->query($sql);
        //Fetching all objects
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($users);
    } catch(PDOException $e) {
        echo '[{ "error": { "info":'.$e->getMessage().'}}]';
    }
});

//Get single user request
$app->get('/api/user/{id}', function(Request $request, Response $response ){
    //Getting Attributes
    $id = $request->getAttribute('id');
    //Statement to be executed
    $sql = "SELECT * FROM user WHERE id = $id";

    try {
        //Get db object
        $db = new db();
        //Connect
        $db = $db->connect();
        //Querying
        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch(PDOException $e) {
        echo '[{ "error": { "info":'.$e->getMessage().'}}]';
    }
});

//Insert user request
$app->post('/api/user/post', function(Request $request, Response $response ){
    //Getting Params
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $email = $request->getParam('email');
    $password = $request->getParam('password');
    $isAdmin = $request->getParam('isAdmin');

    $sql = "INSERT INTO user (first_name, last_name, email, password, isAdmin)"
             . " VALUES (:first_name, :last_name, :email, :password, :isAdmin)";

    try {
        //Get db object
        $db = new db();
        //Connect
        $db = $db->connect();
        //Querying
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":last_name", $last_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":isAdmin", $isAdmin);

        $stmt->execute();

        echo '{ "status": { "info": "user_added" } }';
    } catch(PDOException $e) {
        echo '[{ "error": { "info":'.$e->getMessage().'}}]';
    }
});

//Update user request
$app->put('/api/user/put/{id}', function(Request $request, Response $response ){
    $id = $request->getAttribute('id');

    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $email = $request->getParam('email');
    $password = $request->getParam('password');
    $isAdmin = $request->getParam('isAdmin');

    try {
        //Get db object
        $db = new db();
        //Connect
        $db = $db->connect();
        //Querying
        echo "[";
        if(!empty($first_name)) {
            $sql = "UPDATE user SET first_name = :first_name WHERE id = $id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":first_name", $first_name);
            $stmt->execute();
            echo '{ "status": { "info": "user_first_name_updated" } }, ';
        }
        if(!empty($last_name)) {
            $sql = "UPDATE user SET last_name = :last_name WHERE id = $id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":last_name", $last_name);
            $stmt->execute();
            echo '{ "status": { "info": "user_last_name_updated" } }, ';
        }
        if(!empty($email)) {
            $sql = "UPDATE user SET email = :email WHERE id = $id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            echo '{ "status": { "info": "user_email_updated" } }, ';
        }
        if(!empty($password)) {
            $sql = "UPDATE user SET password = :password WHERE id = $id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":password", $password);
            $stmt->execute();
            echo '{ "status": { "info": "user_password_updated" } }, ';
        }
        if(!empty($isAdmin)) {
            $sql = "UPDATE user SET isAdmin = :isAdmin WHERE id = $id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":isAdmin", $isAdmin);
            $stmt->execute();
            echo '{ "status": { "info": "user_isAdmin_updated" } }, ';
        }
        echo '{ "status": { "info": "user_updated" } }';
        echo "]";
    } catch(PDOException $e) {
        echo '[{ "error": { "info":'.$e->getMessage().'}}]';
    }
});

//Delete user request
$app->delete('/api/user/delete/{id}', function(Request $request, Response $response ){
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM user WHERE id = $id";

    try {
        //Get db object
        $db = new db();
        //Connect
        $db = $db->connect();
        //Querying
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '[{ "status": { "info": "user_' . $id . '_deleted" } }]';
    } catch(PDOException $e) {
        echo '[{ "error": { "info":' . $e->getMessage() . '}}]';
    }
    });
?>