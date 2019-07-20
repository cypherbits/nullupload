<?php

$app->get('/delete-{id:[a-z0-9]{8,10}}-{deletePassword:[a-z0-9]{8,14}}', function ($request, $response, $args) {

    $file = FilesQuery::create()->filterById($args['id'])->findOne();

    if ($file != null) {
        if (hash_equals(hash("sha256", $args['deletePassword']), $file->getDeletepassword())) {

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
