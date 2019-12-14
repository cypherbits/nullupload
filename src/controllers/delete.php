<?php

use nullupload\DB;

$app->get('/delete-{id:[a-z0-9]{8,10}}-{deletePassword:[a-z0-9]{8,14}}', function ($request, $response, $args) {

    $stm = DB::getDB()->prepare("select * from files where id = ? limit 1");
    $stm->bindParam(1,$args['id'], PDO::PARAM_STR);
    $stm->execute();

    $file = $stm->fetch();

    if ($file != null) {
        if (hash_equals(hash("sha256", $args['deletePassword']), $file['deletePassword'])) {

            $path = __DIR__ . "/../../uploads/";

            if (IOHelper::delete($file, $path)) {
                return $this->view->render($response, 'delete.html', [
                            'msg' => 'File with id ' . $file->getId() . ' deleted'
                ]);
            }
        }
    }

    return $response->withRedirect('./');
})->setName("delete");
